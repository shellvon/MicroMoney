<?php
/**
 * Created by PhpStorm.
 * User: shellvon
 * Date: 16/4/15
 * Time: 上午2:02.
 */

namespace Utility;

class ValidateHelper
{
    protected $validator_rules = array();
    protected $data_for_validate = array();
    protected $errors = array();

    /**
     * 根据验证规则验证指定的数据是否符合该规则.
     *
     * @param array $input      需要验证的数组.
     * @param array $validators 验证规则.
     *
     * @return array|bool 成功返回true，否则返回错误信息数组.
     */
    public function isValid(array $input, array $validators)
    {
        foreach ($input as $field => $value) {
            $this->addData($field, $value);
        }
        foreach ($validators as $item) {
            $this->addValidator($item);
        }
        $validated = $this->clearError()->validate();

        return $validated === true ? true : $this->getReadableErrors(false);
    }

    /**
     * 重新指定新的需要验证的数据.
     *
     * @param array $data 需要验证的数据数组.
     *
     * @return ValidateHelper 支持链式调用.
     */
    public function resetData(array $data)
    {
        $this->data_for_validate = $data;

        return $this;
    }

    /**
     * 重新设置新的验证规则.
     *
     * @param array $validators 新的验证规则.
     *
     * @return ValidateHelper 支持链式调用.
     */
    public function resetValidators(array $validators)
    {
        $this->validator_rules = $validators;

        return $this;
    }

    /**
     * 清空错误记录.
     *
     * @return ValidateHelper 支持链式调用.
     */
    public function clearError()
    {
        $this->errors = array();

        return $this;
    }

    /**
     * 添加新的验证规则.
     *
     * @param array $validator 验证规则.
     *
     * @return ValidateHelper 支持链式调用.
     */
    public function addValidator(array $validator)
    {
        $this->validator_rules[] = $validator;

        return $this;
    }

    /**
     * 添加新的验证数据.
     *
     * @param string $field 验证数据的key.
     * @param mixed  $value 验证数据.
     *
     * @return ValidateHelper 支持链式调用.
     */
    public function addData($field, $value)
    {
        $this->data_for_validate[$field] = $value;

        return $this;
    }

    /**
     * 利用验证规则验证数据并返回验证结果.
     *
     * @throws \Exception 当验证规则不存在时抛出Exception.
     *
     * @return bool 成功返回true,否则返回false.
     */
    private function validate()
    {
        foreach ($this->validator_rules as $key => $rule) {
            if ((!isset($rule[0]) || empty($rule[0])) && !isset($rule[1]) || empty($rule[1])) {
                continue;
            }
            $fields = explode(',', $rule[0]);
            $method = 'validate'.ucfirst($rule[1]);
            $params = array_slice($rule, 2);
            if (is_callable(array($this, $method))) {
                foreach ($fields as $field) {
                    $this->$method($field, $params);
                }
            } else {
                throw new \Exception('Unknow function name:'.$method);
            }
        }

        return count($this->getErrors()) > 0 ? false : true;
    }

    /**
     * 通过指定的域获取数据，如果该域不存在于设置的验证数据中返回null.
     *
     * @param string $field 验证数据中存在的key.
     *
     * @return mixed|null
     */
    private function getFieldValue($field)
    {
        if (isset($this->data_for_validate[$field])) {
            return $this->data_for_validate[$field];
        }

        return;
    }

    /**
     * 加入错误信息.
     *
     * @param array $error 加入错误记录信息.
     */
    private function addErrors($error)
    {
        $this->errors[] = $error;
    }

    /**
     * 获取错误信息.
     *
     * @return array 对机器友好的错误数据，如果给人读应该使用getReadableError();
     */
    private function getErrors()
    {
        return $this->errors;
    }

    /**
     * 通过给定的数据组装错误信息数据.
     *
     * @param string $field  验证数据中的key.
     * @param mixed  $param  验证规则的参数.
     * @param string $method 验证规则名字.
     *
     * @return array 组装好了的错误数据.
     */
    private function getReturnArr($field, $param, $method)
    {
        return array(
            'field' => $field,
            'param' => $param,
            'rule' => $method,
        );
    }

    /**
     * 存在性验证.
     *
     * @param string $field 验证数据中的key.
     * @param mixed  $param 此参数不用.
     */
    protected function validateRequired($field, $param)
    {
        $data = $this->getFieldValue($field);
        if ($data === null || $data === '' || $data === array()) {
            $this->addErrors($this->getReturnArr($field, $param, __FUNCTION__));
        }
    }

    /**
     * 长度验证.
     *
     * 函数接受形如array('max'=>10,'min'=>1)的扩展参数,
     * max指定最大长度，min指定最小长度.
     *
     * @param string $field 验证数据中的key.
     * @param array  $param 保存最大/小长度的数组.
     */
    protected function validateLength($field, $param)
    {
        $data = $this->getFieldValue($field);
        if ($data == null) {
            return;
        }
        $str_len = strlen($data);
        if (isset($param['max']) && is_numeric($param['max']) && ($str_len > (int) $param['max'])) {
            $this->addErrors($this->getReturnArr($field, $param, __FUNCTION__));
        }
        if (isset($param['min']) && is_numeric($param['min']) && ($str_len < (int) $param['min'])) {
            $this->addErrors($this->getReturnArr($field, $param, __FUNCTION__));
        }
    }

    /**
     * 范围验证.
     *
     * 函数接受形如array('max'=>10,'min'=>1)的扩展参数,
     * max指定最大长度，min指定最小值.
     *
     * @param string $field 验证数据中的key.
     * @param array  $param 保存最大/小值的数组.
     */
    protected function validateRange($field, $param)
    {
        $data = $this->getFieldValue($field);
        if (is_numeric($data) && isset($param['max']) && is_numeric($param['max'])) {
            if ($data > $param['max']) {
                $this->addErrors($this->getReturnArr($field, $param, __FUNCTION__));
            }
        }
        if (is_numeric($data) && isset($param['min']) && is_numeric($param['min'])) {
            if ($data < $param['min']) {
                $this->addErrors($this->getReturnArr($field, $param, __FUNCTION__));
            }
        }
    }

    /**
     * 正则表达式验证.
     *
     * 函数接受形如array('reg'=>'/\d+/')的扩展参数,
     * reg指定所使用的正则表达式.
     *
     * @param string $field 验证数据中的key.
     * @param array  $param 含有key为reg的正则表达式.
     */
    protected function validateUseRegex($field, $param)
    {
        $data = $this->getFieldValue($field);
        // filter_var($data, FILTER_VALIDATE_REGEXP, $option)
        if (($data != null) && (!preg_match($param['reg'], $data))) {
            $this->addErrors($this->getReturnArr($field, $param, __FUNCTION__));
        }
    }

    /**
     * 邮箱验证.
     *
     * @param string $field 验证数据中的key.
     * @param mixed  $param 该函数不需要指定扩展参数.
     */
    protected function validateEmail($field, $param)
    {
        $data = $this->getFieldValue($field);
        if (($data != null) && (filter_var($data, FILTER_VALIDATE_EMAIL) === false) || $data === '') {
            $this->addErrors($this->getReturnArr($field, $param, __FUNCTION__));
        }
    }

    /**
     * 整数验证.
     *
     * @param string $field 验证数据中的key.
     * @param mixed  $param 该函数不需要指定扩展参数.
     */
    protected function validateInteger($field, $param)
    {
        // http://wisercoder.com/check-for-integer-in-php/
        $data = $this->getFieldValue($field);
        if (($data != null) && (filter_var($data, FILTER_VALIDATE_INT) === false) || $data === '') {
            $this->addErrors($this->getReturnArr($field, $param, __FUNCTION__));
        }
    }

    /**
     * 浮点数验证.
     *
     * @param string $field 验证数据中的key.
     * @param mixed  $param 该函数不需要指定扩展参数.
     */
    protected function validateFloat($field, $param)
    {
        $data = $this->getFieldValue($field);
        if (($data != null) && (filter_var($data, FILTER_VALIDATE_FLOAT) === false) || $data === '') {
            $this->addErrors($this->getReturnArr($field, $param, __FUNCTION__));
        }
    }

    /**
     * 布尔型验证.
     *
     * @param string $field 验证数据中的key.
     * @param mixed  $param 该函数不需要指定扩展参数.
     */
    protected function validateBoolean($field, $param)
    {
        $data = $this->getFieldValue($field);
        if (($data != null) && (filter_var($data, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === null)) {
            $this->addErrors($this->getReturnArr($field, $param, __FUNCTION__));
        }
    }

    /**
     * 日期验证.
     *
     * @param string $field 验证数据中的key.
     * @param mixed  $param 该函数不需要指定扩展参数.
     */
    protected function validateDate($field, $param)
    {
        $data = $this->getFieldValue($field);
        if ($data == null) {
            return;
        }
        $cdate1 = date('Y-m-d', strtotime($data));
        $cdate2 = date('Y-m-d H:i:s', strtotime($data));
        if ($cdate1 != $data && $cdate2 != $data) {
            $this->addErrors($this->getReturnArr($field, $param, __FUNCTION__));
        }
    }

    /**
     * 枚举成员检查，使用in_array($field, $haystack, $strict)进行检查.
     *
     * @param string $field 验证数据中的key.
     * @param mixed  $param 保存允许的枚举数组以及是否需要执行严格检查.
     */
    protected function validateEnums($field, $param)
    {
        $data = $this->getFieldValue($field);
        $strict = isset($param['strict']) ? $param['strict'] : false;
        if (($data != null) && (!in_array($data, $param['haystack'], $strict))) {
            $this->addErrors($this->getReturnArr($field, $param, __FUNCTION__));
        }
    }

    /**
     * 返回可读的错误信息.
     *
     * @param bool $to_string 是否需要转化为string.
     *
     * @return array|string 默认返回数组，可由参数决定是否为string.
     */
    public function getReadableErrors($to_string = false)
    {
        $msg = array();
        foreach ($this->errors as $error) {
            switch ($error['rule']) {
                case 'validateRequired':
                    $msg[] = '参数'.$error['field'].'不能为空!';
                    break;
                case 'validateLength':
                    $msg[] = '参数'.$error['field'].'长度不对';
                    break;
                case 'validateInteger':
                    $msg[] = '参数'.$error['field'].'应该为整数';
                    break;
                case 'validateRange':
                    $msg[] = '参数'.$error['field'].'不满足范围要求';
                    break;
                case 'validateFloat':
                    $msg[] = '参数'.$error['field'].'应该为浮点数';
                    break;
                case 'validateBoolean':
                    $msg[] = '参数'.$error['field'].'应该为布尔值';
                    break;
                case 'validateUseRegex':
                    $msg[] = '参数'.$error['field'].'格式不正确';
                    break;
                case 'validateDate':
                    $msg[] = '参数'.$error['field'].'应该为时间格式';
                    break;
                case 'validateEmail':
                    $msg[] = '参数'.$error['field'].'应该为邮箱格式';
                    break;
                default:
                    $msg[] = '参数'.$error['field'].'不正确';
                    break;
            }
        }
        if ($to_string) {
            return implode("\n", $msg);
        }

        return $msg;
    }

    /**
     * PHP的Magic函数.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getReadableErrors(true);
    }
}
