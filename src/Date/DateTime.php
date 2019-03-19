<?php
declare(strict_types=1);

namespace Timeline\Date;

class DateTime extends \DateTime
{
    /**
     * @var DateTimeFormatter
     */
    private $formatter;

    /**
     * DateTime constructor.
     *
     * @param  string             $time
     * @param  \DateTimeZone|null $timeZone
     * @param  DateTimeFormatter  $formatter
     * @throws \Exception
     */
    public function __construct(
        string $time = "now",
        ?\DateTimeZone $timeZone = null,
        ?DateTimeFormatter $formatter = null
    ) {
        $this->formatter = $formatter ?? DateTimeFormatter::createDefault();
        parent::__construct($time, $timeZone);
    }

    public function __toString(): string
    {
        return $this->formatter->format($this);
    }
}
