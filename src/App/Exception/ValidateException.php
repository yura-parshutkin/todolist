<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidateException extends HttpException
{
    /**
     * @var array
     */
    protected $errors;

    /**
     * @param ConstraintViolationListInterface|array $errors
     * @param null $message
     * @param \Exception|null $previous
     * @param int $code
     */
    public function __construct($errors, $message = null, \Exception $previous = null, $code = 0)
    {
        parent::__construct(403, $message, $previous, array(), $code);

        $this->errors = [];
        if ($errors instanceof ConstraintViolationListInterface) {
            foreach ($errors as $error) {
                $this->errors[$error->getPropertyPath()] = $error->getMessage();
            }
        } elseif (is_array($this->errors)) {
            $this->errors = $errors;
        }
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}