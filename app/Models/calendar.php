<?php

namespace Models;

class calendar
{

    private $months = [
        1 => 'Janvier',
        2 => 'Février',
        3 => 'Mars',
        4 => 'Avril',
        5 => 'Mai',
        6 => 'Juin',
        7 => 'Juillet',
        8 => 'Août',
        9 => 'Septembre',
        10 => 'Octobre',
        11 => 'Novembre',
        12 => 'Décembre'
    ];
    public int|null $year;
    public int|null $month;
    public int|null $week;

    /**
     * @param int|null $year l'année
     * @param int|null $month le mois compris entre 1 et 12
     * @param int|null $week les week de 1 à 6
     *@throws \Exception
     */
    public function __construct(?int $year = 0, ?int $month = null, ?int $week = null)
    {
        $month = $month == 0 ? date("m") : $month;
        $month = $month < 1 || $month > 12 ? throw new \Exception("Le mois n'est pas valide") : $month;

        $year = $year == 0 ? date('Y') : $year;
        $year = $year < 1970 ? throw new \Exception("l'année est inférieur à 1970") : $year;

        $week = $week == 0 ? date('W') : $week;
        $week = $week < 1 || $week > 52 ? throw new \Exception("la semaine n\'est pas valide" . $week) : $week;

        $this->year = $year;
        $this->month = $month;
        $this->week = $week;
    }


    /**
     * @throws \DateMalformedStringException
     * @return array la liste des dates en français d'une semaine
     */

    public function getWeekDates(): array
    {
        setlocale(LC_TIME, 'fr_FR.UTF-8');
        $formatter = new \IntlDateFormatter('fr_FR', \IntlDateFormatter::FULL, \IntlDateFormatter::NONE, 'Europe/Paris', \IntlDateFormatter::GREGORIAN, 'EEEE d MMMM');

        $dates = [];
        $firstDay = new \DateTime();
        $firstDay->setISODate($this->year, $this->week);

        for ($i = 0; $i < 6; $i++) {
            $dates[] = $formatter->format($firstDay);
            $firstDay->modify('+1 day');
        }

        return $dates;
    }

    public function getMonth(): string
    {
        return $this->months[$this->month];
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getWeek(): int
    {
        return $this->week;
    }

    /**
     * @throws \DateMalformedStringException
     * @throws \Exception
     */
    public function nextWeek(): calendar
    {
        $firstDayOfWeek = new \DateTime();
        $firstDayOfWeek->setISODate($this->year, $this->week);
        $firstDayOfWeek->modify('+1 week');
        return new calendar(
            (int)$firstDayOfWeek->format('Y'),
            (int)$firstDayOfWeek->format('m'),
            (int)$firstDayOfWeek->format('W')
        );
    }

    /**
     * @throws \DateMalformedStringException
     * @throws \Exception
     */
    public function previousWeek(): calendar
    {
        $firstDayOfWeek = new \DateTime();
        $firstDayOfWeek->setISODate($this->year, $this->week);
        $firstDayOfWeek->modify('-1 week');

        return new calendar(
            (int)$firstDayOfWeek->format('Y'),
            (int)$firstDayOfWeek->format('m'),
            (int)$firstDayOfWeek->format('W')
        );
    }

}
