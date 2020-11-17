<?php declare(strict_types=1);

namespace Circli\WebCore\Exception;

trait MessageCodeTrait
{
    private string $messageCode = '';

    public function getMessageCode(): string
    {
        if (!$this->messageCode) {
            $classParts = explode('\\', get_called_class());
            $class = (string)array_pop($classParts);
            $const = preg_replace('/(?<=\d)(?=[A-Za-z])|(?<=[A-Za-z])(?=\d)|(?<=[a-z])(?=[A-Z])/', '_', $class);
            return strtoupper($const);
        }

        return $this->messageCode;
    }
}
