<?php

namespace System;

class Validation
{
    /**
     * Application Object
     *
     * @var \System\Application
     */
    private $app;

    /**
     * Errors container
     *
     * @var array
     */
    private $errors = [];

    /**
     * Constructor
     *
     * @param \System\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Determine if the given input is not empty
     *
     * @param string $input
     * @param string $customErrorMessage
     * @param bool $isValue
     * @return $this
     */
    public function required($input, $customErrorMessage = null, $isValue = false)
    {
        if ($this->hasErrors($input)) {
            return $this;
        }

        $value = $input;
        $input = !$isValue ? $input : 'some fields';

        if (!$isValue) {
            $value = $this->value($input);
        }

        if ($value === '') {
            $defaultMessage = sprintf('%s is required', ucfirst($input));
            $message = $customErrorMessage ?: $defaultMessage;
            $this->addError($input, $message);
        }

        return $this;
    }

    /**
     * Determine if the given input file exists
     *
     * @param mixed $input
     * @param string $customErrorMessage$user[
     */
    public function requiredFile($input, $customErrorMessage = null, $isValue = false)
    {
        if ($this->hasErrors($input)) {
            return $this;
        }

        $file = $input;
        $input = !$isValue ? $input : 'file';

        if (!$isValue) {
            $file = $this->app->request->file($input);
        }

        if (!$file->exists()) {
            $defaultMessage = sprintf('%s is required', ucfirst($input));
            $message = $customErrorMessage ?: $defaultMessage;
            $this->addError($input, $message);
        }

        return $this;
    }

    /**
     * Determine if the given input is an image
     *
     * @param string $input
     * @param string $customErrorMessage
     * @param bool $isValue
     * @return $this
     */
    public function image($input, $customErrorMessage = null, $isValue = false)
    {
        if ($this->hasErrors($input)) {
            return $this;
        }

        $file = $input;
        $input = !$isValue ? $input : 'file';

        if (!$isValue) {
            $file = $this->app->request->file($input);
        }

        if (!$file->exists()) {
            return $this;
        }

        if (!$file->isImage()) {
            $defaultMessage = sprintf('%s is not valid image', ucfirst($input));
            $message = $customErrorMessage ?: $defaultMessage;
            $this->addError($input, $message);
        }

        return $this;
    }

    /**
     * Determine if the given input is an json
     *
     * @param string $input
     * @param string $customErrorMessage
     * @param bool $isValue
     * @return $this
     */
    public function json($input, $customErrorMessage = null, $isValue = false)
    {
        if ($this->hasErrors($input)) {
            return $this;
        }

        $file = $input;
        $input = !$isValue ? $input : 'file';

        if (!$isValue) {
            $file = $this->app->request->file($input);
        }

        if (!$file->exists()) {
            return $this;
        }

        if (!$file->isJson()) {
            $defaultMessage = sprintf('%s is not valid json', ucfirst($input));
            $message = $customErrorMessage ?: $defaultMessage;
            $this->addError($input, $message);
        }

        return $this;
    }

    /**
     * Determine if the given input is valid email
     *
     * @param string $input
     * @param string $customErrorMessage
     * @param bool $isValue
     * @return $this
     */
    public function email($input, $customErrorMessage = null, $isValue = false)
    {
        if ($this->hasErrors($input)) {
            return $this;
        }

        $value = $input;
        $input = !$isValue ? $input : 'email';

        if (!$isValue) {
            $value = $this->value($input);
        }

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $defaultMessage = sprintf('%s is not valid', ucfirst($input));
            $message = $customErrorMessage ?: $defaultMessage;
            $this->addError($input, $message);
        }

        return $this;
    }

    /**
     * Determine if the given input has is only a-z || A-Z
     *
     * @param string $input
     * @param string $customErrorMessage
     * @param bool $isValue
     * @return $this
     */
    public function text($input, $customErrorMessage = null, $isValue = false)
    {
        if ($this->hasErrors($input)) {
            return $this;
        }

        $value = $input;
        $input = !$isValue ? $input : 'some fields';

        if (!$isValue) {
            $value = $this->value($input);
        }

        if (!preg_match('/^[a-zA-Z0-9,-:\'" ]*$/', $value)) {
            $defaultMessage = sprintf('%s is not valid', ucfirst($input));
            $message = $customErrorMessage ?: $defaultMessage;
            $this->addError($input, $message);
        }

        return $this;
    }

    /**
     * Determine if the given input has valid phone number
     *
     * @param string $input
     * @param string $customErrorMessage
     * @param bool $isValue
     * @return $this
     */
    public function phone($input, $customErrorMessage = null, $isValue = false)
    {
        if ($this->hasErrors($input)) {
            return $this;
        }

        $value = $input;
        $input = !$isValue ? $input : 'phone';

        if (!$isValue) {
            $value = $this->value($input);
        }

        if (!preg_match('/^\+[0-9]{10,}$/', $value)) {
            $defaultMessage = sprintf('%s number invalid', ucfirst($input));
            $message = $customErrorMessage ?: $defaultMessage;
            $this->addError($input, $message);
        }

        return $this;
    }

    /**
     * Determine if the given input has bool in int format (0||1) value
     *
     * @param string $input
     * @param string $customErrorMessage
     * @param bool $isValue
     * @return $this
     */
    public function isFormatIntBool($input, $customErrorMessage = null, $isValue = false)
    {
        if ($this->hasErrors($input)) {
            return $this;
        }

        $value = $input;
        $input = !$isValue ? $input : 'some fields';

        if (!$isValue) {
            $value = $this->value($input);
        }

        $value = intval($input);

        if ($value !== 1 && $value !== 0) {
            $defaultMessage = sprintf('%s Accepts only 0 or 1', ucfirst($input));
            $message = $customErrorMessage ?: $defaultMessage;
            $this->addError($input, $message);
        }

        return $this;
    }

    /**
     * Determine if the given input has int value
     *
     * @param string $input
     * @param string $customErrorMessage
     * @param bool $isValue
     * @return $this
     */
    public function int($input, $customErrorMessage = null, $isValue = false)
    {
        if ($this->hasErrors($input)) {
            return $this;
        }

        $value = $input;
        $input = !$isValue ? $input : 'some fields';

        if (!$isValue) {
            $value = $this->value($input);
        }

        $value = intval($input);

        if (!is_int($value)) {
            $defaultMessage = sprintf('%s Accepts only int', ucfirst($input));
            $message = $customErrorMessage ?: $defaultMessage;
            $this->addError($input, $message);
        }

        return $this;
    }

    /**
     * Determine if the given input has float value
     *
     * @param string $input
     * @param string $customErrorMessage
     * @param bool $isValue
     * @return $this
     */
    public function float($input, $customErrorMessage = null, $isValue = false)
    {
        if ($this->hasErrors($input)) {
            return $this;
        }

        $value = $input;
        $input = !$isValue ? $input : 'some fields';

        if (!$isValue) {
            $value = $this->value($input);
        }

        if (!is_float(floatval($value))) {
            $defaultMessage = sprintf('%s Accepts only floats', ucfirst($input));
            $message = $customErrorMessage ?: $defaultMessage;
            $this->addError($input, $message);
        }

        return $this;
    }

    /**
     * Determine if the given input value should be at least the given length
     *
     * @param string $input
     * @param int $length
     * @param string $customErrorMessage
     * @param bool $isValue
     * @return $this
     */
    public function minLen($input, $length, $customErrorMessage = null, $isValue = false)
    {
        if ($this->hasErrors($input)) {
            return $this;
        }

        $value = $input;
        $input = !$isValue ? $input : 'some fields';

        if (!$isValue) {
            $value = $this->value($input);
        }

        if (strlen($value) < $length) {
            $defaultMessage = sprintf('%s should be at least %d', ucfirst($input), $length);
            $message = $customErrorMessage ?: $defaultMessage;
            $this->addError($input, $message);
        }

        return $this;
    }

    /**
     * Determine if the given input value should be at most the given length
     *
     * @param string $input
     * @param int $length
     * @param string $customErrorMessage
     * @param bool $isValue
     * @return $this
     */
    public function maxLen($input, $length, $customErrorMessage = null, $isValue = false)
    {
        if ($this->hasErrors($input)) {
            return $this;
        }

        $value = $input;
        $input = !$isValue ? $input : 'some fields';

        if (!$isValue) {
            $value = $this->value($input);
        }

        if (strlen($value) > $length) {
            $defaultMessage = sprintf('%s should be at most %d', ucfirst($input), $length);
            $message = $customErrorMessage ?: $defaultMessage;
            $this->addError($input, $message);
        }

        return $this;
    }

    /**
     * Determine if the first input matches the second input
     *
     * @param string $fistInput
     * @param string $secondInput
     * @param string $customErrorMessage
     * @param bool $isValue
     * @return $this
     */
    public function match($firstInput, $secondInput, $customErrorMessage = null, $isValue = false)
    {

        $firstValue = $firstInput;
        $secondValue = $secondInput;
        $firstInput = !$isValue ? $firstInput : 'some fields';
        $secondInput = !$isValue ? $secondInput : 'some fields';

        if (!$isValue) {
            $firstValue = $this->value($firstInput);
            $secondValue = $this->value($secondInput);
        }

        if ($firstValue != $secondValue) {
            $defaultMessage = sprintf('%s should match %s', ucfirst($secondInput), ucfirst($firstInput));
            $message = $customErrorMessage ?: $defaultMessage;
            $this->addError($secondInput, $message);
        }

        return $this;
    }

    /**
     * Determine if the given input is unique in database
     *
     * @param string $input
     * @param array $databaseData
     * @param string $customErrorMessage
     * @param bool $isValue
     * @return $this
     */
    public function unique($input, array $databaseData, $customErrorMessage = null, $isValue = false)
    {
        if ($this->hasErrors($input)) {
            return $this;
        }

        $value = $input;
        $input = !$isValue ? $input : 'some fields';

        if (!$isValue) {
            $value = $this->value($input);
        }

        $table = null;
        $column = null;
        $exceptionColumn = null;
        $exceptionColumnValue = null;

        if (count($databaseData) == 2) {
            list($table, $column) = $databaseData;
        } elseif (count($databaseData) == 4) {
            list($table, $column, $exceptionColumn, $exceptionColumnValue) = $databaseData;
        }

        if ($exceptionColumn and $exceptionColumnValue) {
            $result = $this->app->db->select($column)
                ->from($table)
                ->where($column . ' = ? AND ' . $exceptionColumn . ' != ?', $value, $exceptionColumnValue)
                ->fetch();
        } else {
            $result = $this->app->db->select($column)
                ->from($table)
                ->where($column . ' = ?', $value)
                ->fetch();
        }

        if ($result) {
            $defaultMessage = sprintf('%s already exists', ucfirst($input));
            $message = $customErrorMessage ?: $defaultMessage;
            $this->addError($input, $message);
        }
    }

    /**
     * Add Custom Message
     *
     * @param string $message
     * @return $this
     */
    public function message($message)
    {
        $this->errors[] = $message;

        return $this;
    }

    /**
     * Determine if there are any invalid inputs
     *
     * @return bool
     */
    public function fails()
    {
        return !empty($this->errors);
    }

    /**
     * Determine if all inputs are valid
     *
     * @return bool
     */
    public function passes()
    {
        return empty($this->errors);
    }

    /**
     * Get All errors
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->errors;
    }

    /**
     * Get the value for the given input name
     *
     * @param string $input
     * @return mixed
     */
    private function value($input)
    {
        $value = $this->app->request->post($input, '');

        if ($value === '') {
            $value = $this->app->request->fileGetContents($input);
        }

        return $value;
    }

    /**
     * Add input error
     *
     * @param string $input
     * @param string $errorMessage
     * @return void
     */
    private function addError($input, $errorMessage)
    {
        $this->errors[$input] = $errorMessage;
    }

    /**
     * Determine if the given input has previous errors
     *
     * @param string $input
     */
    private function hasErrors($input)
    {
        return array_key_exists($input, $this->errors);
    }

    /**
     * get $errors
     *
     * @return string
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
