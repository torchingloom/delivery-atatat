<?php

namespace Domain\Collection;

class Collection extends \Domain\Collection
{
    protected
        $params,
        $dataType,
        $contentTotalCount,
        $resource = array(),
        $childs = array(),
        $dataSourceCall = array()
    ;
    
    public function __construct($params = array())
    {
        $this->params = $params;
        $this->prepare();
        $this->fill();
    }

    public function getTotalCount()
    {
        return $this->contentTotalCount;
    }

    protected function prepare()
    {
        $this->prepareDataTypeDeclaration();
        $this->prepareResourceDeclaretion();
        $this->prepareDataSourceCallDeclaretion();
        $this->prepareChildrenDeclaretion();
    }

    /**
     * Store collection items to DB
     *
     * @param array $data
     * @return mixed
     * @throws CollectionException
     */
    public function store(array $data = null, array $args = null)
    {
        if (!empty($this->dataSourceCall['methodStore']))
        {
            if (!$data)
            {
                $data = $this->toArray();
            }
            $db = \Service\Registry::get("db_{$this->dataSourceCall['methodStore']['db']}");
            $result = call_user_func_array(array($db, $this->dataSourceCall['methodStore']['method']), array($data));
            $this->fill();
            return $result;
        }
        else
        {
            throw new CollectionException("\n\nНе ясно как пытаться сохранить данные для коллекции : ". __NAMESPACE__ ."/". __CLASS__ ." \n\nТакие дела");
        }
    }

    public function remove(array $data)
    {
        if (!empty($this->dataSourceCall['methodRemove']))
        {
            $db = \Service\Registry::get("db_{$this->dataSourceCall['methodRemove']['db']}");
            $result = call_user_func_array(array($db, $this->dataSourceCall['methodRemove']['method']), array($data));
            $this->fill();
            return $result;
        }
        else
        {
            throw new CollectionException("\n\nНе ясно как пытаться удалять данные для коллекции : ". __NAMESPACE__ ."/". __CLASS__ ." \n\nТакие дела");
        }
    }

    protected function fill()
    {
        if (!empty($this->dataSourceCall['methodGet']))
        {
            if (empty($this->dataSourceCall['params']['empty']))
            {
                $db = \Service\Registry::get("db_{$this->dataSourceCall['methodGet']['db']}");
                $this->content = call_user_func_array(array($db, $this->dataSourceCall['methodGet']['method']), !empty($this->dataSourceCall['params']) ? array($this->dataSourceCall['params']) : array());
                if (!empty($this->dataSourceCall['methodTotalCount']))
                {
                    $this->contentTotalCount = call_user_func_array(array($db, $this->dataSourceCall['methodTotalCount']), !empty($this->dataSourceCall['params']) ? array($this->dataSourceCall['params']) : array());
                }
                $this->fillChilds();
                $this->fillResources();
            }
        }
        else
        {
            throw new CollectionException("\n\nНе ясно как овладеть данными для коллекции : ".__NAMESPACE__."/".__CLASS__." \n\nТакие дела");
        }
    }

    protected function fillChilds()
    {
        if ($this->childs)
        {
            foreach ($this->childs AS $entity => &$child)
            {
                $db = \Service\Registry::get($child['db']);

                $keyParent = explode(':', $child['relation']['parent']);
                if (count($keyParent) < 2)
                {
                    $keyParent[] = $keyParent[0];
                }
                $keyChild = $child['relation']['child'];
                foreach ($this->content AS $entity)
                {
                    $child['params'][$keyParent[1]][] = $entity->{$keyParent[0]};
                }

                if ($aChilds = call_user_func_array(array($db, $child['method']), array($child['params'])))
                {
                    $aChildsTotalCount = $child['methodTotalCount'] ? call_user_func_array(array($db, $child['methodTotalCount']), array($child['params'])) : null;

                    foreach ($aChilds AS &$oChild)
                    {
                        foreach ($this->content AS $entity)
                        {
                            if ($oChild->{$keyChild} == $entity->{$keyParent[0]})
                            {
                                $entity->{$child['appendMethod']}($oChild);
                            }
                            if ($aChildsTotalCount)
                            {
                                foreach ($aChildsTotalCount AS $count)
                                {
                                    if ($count->{$keyChild} == $entity->{$keyParent[0]})
                                    {
                                        $entity->{$child['appendTotalCountMethod']}($count->{$child['appendTotalCountValueField']});
                                    }
                                }
                            }
                        }
                    }

                    if (@$child['resource'])
                    {
                        $keyParent = $child['resource']['parent'];
                        $keyChild = $child['resource']['child'];
                        foreach ($aChilds AS &$oChildForResource)
                        {
                            $child['resource'][$child['resource']['parent']][] = $oChildForResource->{$child['resource']['parent']};
                        }

                        $aResources = call_user_func_array(array($db, $child['resource']['method']), array($child['resource']));
                        foreach ($aResources AS $oResource)
                        {
                            foreach ($aChilds AS &$entity)
                            {
                                if ($oResource->{$keyChild} == $entity->{$keyParent})
                                {
                                    $entity->appendResource($oResource);
                                }
                            }
                        }
                    }
                }
            }
            $this->rewind();
        }
    }

    protected function fillResources()
    {
        if ($this->resource)
        {
            $keyParent = $this->resource['parent'];
            $keyChild = $this->resource['child'];
            $db = \Service\Registry::get('db');
            foreach ($this AS $entity)
            {
                $this->resource[$this->resource['parent']][] = $entity->{$this->resource['parent']};
            }
            foreach (call_user_func_array(array($db, $this->resource['method']), array($this->resource)) AS $oChild)
            {
                foreach ($this->content AS $entity)
                {
                    if ($oChild->{$keyChild} == $entity->{$keyParent})
                    {
                        $entity->appendResource($oChild);
                    }
                }
            }
        }
    }

    /**
     * prepare $this->params['DataType']
     * @throws CollectionException
     * @return void
     */
    protected function prepareDataTypeDeclaration()
    {
        // create data type object
        if (!empty($this->params['DataType']))
        {
            if (file_exists($fname = "../Library/DataType/Meta/{$this->params['DataType']}.xml"))
            {
                $this->dataType = new \Service\XML_Query($fname);
            }
            else
            {
                throw new CollectionException(__NAMESPACE__.'\\'.__METHOD__ . $fname);
            }
        }
    }

    protected function prepareResourceDeclaretion()
    {
        if ($resourcetag = $this->dataType->getNodeByPath('/type/resources'))
        {
            $this->resource = $resourcetag->getAttributes();
            foreach($this->dataType->getNodeListByPath('/type/resources/*') AS $_resource)
            {
                $this->resource['type'][$_resource->getTagName()] = $_resource->getAttribute('count') ?: 1;
            }
        }
    }

    /**
     * prepare $this->dataSourceCall
     * @return void
     */
    protected function prepareDataSourceCallDeclaretion()
    {
        $this->dataSourceCall['methodGet']['method'] = $this->dataType->getNodeByPath('/type/dataSource/method')->getNodeValue();
        $this->dataSourceCall['methodGet']['db'] = 'default';
        if ($var = $this->dataType->getNodeByPath('/type/dataSource/db'))
        {
            $this->dataSourceCall['methodGet']['db'] = $var->getNodeValue();
        }

        if ($var = $this->dataType->getNodeByPath('/type/dataStore/method'))
        {
            $this->dataSourceCall['methodStore']['method'] = $var->getNodeValue();
            $this->dataSourceCall['methodStore']['db'] = 'default';
            if ($var = $this->dataType->getNodeByPath('/type/dataStore/db'))
            {
                $this->dataSourceCall['methodStore']['db'] = $var->getNodeValue();
            }
        }

        if ($var = $this->dataType->getNodeByPath('/type/dataRemove/method'))
        {
            $this->dataSourceCall['methodRemove']['method'] = $var->getNodeValue();
            $this->dataSourceCall['methodRemove']['db'] = 'default';
            if ($var = $this->dataType->getNodeByPath('/type/dataRemove/db'))
            {
                $this->dataSourceCall['methodRemove']['db'] = $var->getNodeValue();
            }
        }


        if ($totalcountmethod = $this->dataType->getNodeByPath('/type/dataSource/methodTotalCount'))
        {
            $this->dataSourceCall['methodTotalCount'] = $this->dataType->getNodeByPath('/type/dataSource/methodTotalCount')->getNodeValue();
        }


        $this->dataSourceCall['params'] = array();
        foreach ($this->dataType->getNodeListByPath('/type/dataSource/params/*') AS $_param)
        {
            $tag = $_param->getTagName();
            if (array_key_exists($tag, $this->dataSourceCall['params']))
            {
                if (!is_array($this->dataSourceCall['params'][$tag]))
                {
                    $this->dataSourceCall['params'][$tag] = array($this->dataSourceCall['params'][$tag]);
                }
                $this->dataSourceCall['params'][$tag][] = $_param->getNodeValue();
                continue;
            }
            $this->dataSourceCall['params'][$tag] = $_param->getNodeValue();
        }

        foreach ($this->dataType->getNodeListByPath('/type/dataSource/fetch/*') AS $_param)
        {
            $this->dataSourceCall['params']['__FETCH__'][$_param->getTagName()] = $_param->getNodeValue();
        }

        foreach ($this->dataType->getNodeListByPath('/type/dataSource/columns/*') as $_column)
        {
            $this->dataSourceCall['params']['__FIELDS__'][$_column->getTagName()] = $_column->getAttributes();
        }

        $this->dataSourceCall['params'] = array_merge($this->dataSourceCall['params'], (array) @$this->params['Params']);
    }

    /**
     * prepare $this->childs
     * @return void
     */
    protected function prepareChildrenDeclaretion()
    {
        foreach($this->dataType->getNodeListByPath('/type/children/*') AS $_child)
        {
            $tag = $_child->getTagName();
            $this->childs[$tag] = array
            (
                'db' => $_child->getAttribute('db') ? "db_{$_child->getAttribute('db')}" : 'db_default',
                'method' => $_child->getAttribute('method'),
                'appendMethod' => $_child->getAttribute('appendMethod'),
                'methodTotalCount' => $_child->getAttribute('methodTotalCount'),
                'appendTotalCountMethod' => $_child->getAttribute('appendTotalCountMethod'),
                'appendTotalCountValueField' => $_child->getAttribute('appendTotalCountValueField'),
                'relation' => array
                (
                    'parent' => $_child->getAttribute('parent'),
                    'child' => $_child->getAttribute('child')
                ),
                'params' => array()
            );
            foreach ($_child->getNodeListByPath("/type/children/{$tag}/params/*") AS $_param)
            {
                $tagparam = $_param->getTagName();
                if (array_key_exists($tagparam, $this->childs[$tag]['params']))
                {
                    if (!is_array($this->childs[$tag]['params'][$tagparam]))
                    {
                        $this->childs[$tag]['params'][$tagparam] = array($this->childs[$tag]['params'][$tagparam]);
                    }
                    $this->childs[$tag]['params'][$tagparam][] = $_param->getNodeValue();
                    continue;
                }
                $this->childs[$tag]['params'][$tagparam] = $_param->getNodeValue();
            }

            $this->childs[$tag]['params']['__FETCH__'] = array();
            foreach($_child->getNodeListByPath("/type/children/{$tag}/fetch/*") AS $_param)
            {
                $tagparam = $_param->getTagName();
                if (array_key_exists($tagparam, $this->childs[$tag]['params']['__FETCH__']))
                {
                    if (!is_array($this->childs[$tag]['params']['__FETCH__'][$tagparam]))
                    {
                        $this->childs[$tag]['params']['__FETCH__'][$tagparam] = array($this->childs[$tag]['params']['__FETCH__'][$tagparam]);
                    }
                    $this->childs[$tag]['params']['__FETCH__'][$tagparam][] = $_param->getNodeValue();
                    continue;
                }
                $this->childs[$tag]['params']['__FETCH__'][$tagparam] = $_param->getNodeValue();
            }

            foreach($_child->getNodeListByPath("/type/children/{$tag}/columns/*") AS $_column)
            {
                $this->childs[$tag]['params']['__FIELDS__'][$_column->getTagName()] = $_column->getAttributes();
            }

            foreach($_child->getNodeListByPath("/type/children/{$tag}/paramsFromParent/*") AS $_param)
            {
                $_paramtag = $_param->getTagName();
                $_alias = $_param->getAttribute('alias') ?: $_paramtag;
                $this->childs[$tag]['params'][$_alias] = @$this->dataSourceCall['params'][$_paramtag];
            }

            if ($resourcetag = $this->dataType->getNodeByPath("/type/children/{$tag}/resources"))
            {
                $this->childs[$tag]['resource'] = $resourcetag->getAttributes();
                foreach($this->dataType->getNodeListByPath("/type/children/{$tag}/resources/*") AS $_resource)
                {
                    $this->childs[$tag]['resource']['type'][$_resource->getTagName()] = $_resource->getAttribute('count') ?: 1;
                }
            }
        }
    }

}

class CollectionException extends \Domain\Exception
{
}

class CollectionAppendChildException extends \Domain\Exception
{
}