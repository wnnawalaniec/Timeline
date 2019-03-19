<?php
declare(strict_types=1);

namespace Timeline\Date;

class DateTimeFormatter
{
    /**
     * @var string
     */
    private $format;

    /**
     * DateTimeFormatter constructor.
     *
     * @param string $format
     */
    public function __construct(string $format)
    {
        $this->format = $format;
    }

    public function format(\DateTime $time) : string
    {
        return $time->format($this->format);
    }

    public static function createDefault() : DateTimeFormatter
    {
        return new DateTimeFormatter('Y-m-d');
    }
}
