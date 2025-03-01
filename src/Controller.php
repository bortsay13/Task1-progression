<?php

namespace Bortsaykin\Progression;

class Controller
{
    private $conn;

    public function __construct()
    {
        $this->conn = mysqli_connect("localhost", "root", "", "progression_game");

        if (!$this->conn) {
            die("Не удалось соединиться с БД по причине: " . mysqli_connect_error());
        }
    }

    public function generateProgression()
    {
        $start = rand(1, 20);
        $step = rand(1, 10);

        $progression = [];
        for ($i = 0; $i < 10; $i++) {
            $progression[] = $start + ($i * $step);
        }

        $missingIndex = rand(0, 9);
        $missingNumber = $progression[$missingIndex];
        $progression[$missingIndex] = '.';

        return [
            'progression' => $progression,
            'missingIndex' => $missingIndex,
            'missingNumber' => $missingNumber
        ];
    }

    public function startGame()
    {
        $Name = readline("Введите Ваше имя: ");

        $progressionData = $this->generateProgression();
        $progression = implode(' ', $progressionData['progression']);
        echo "Найдите пропущенное число в арифметической прогрессии:\n";
        echo $progression . "\n";

        $playerAnswer = readline("Введите пропущенное число: ");

        if ($playerAnswer == $progressionData['missingNumber']) {
            echo "Поздравляем, Вы правильно нашли число!\n";
            $progressionData['progression'][$progressionData['missingIndex']] = $progressionData['missingNumber'];
            $this->saveResult($Name, true, $progressionData['missingNumber'], implode(' ', $progressionData['progression']));
        } else {
            echo "Неправильный ответ! Вот правильная прогрессия:\n";
            $progressionData['progression'][$progressionData['missingIndex']] = $progressionData['missingNumber'];
            echo implode(' ', $progressionData['progression']) . "\n";
            $this->saveResult($Name, false, $progressionData['missingNumber'], implode(' ', $progressionData['progression']));
        }
    }

    public function saveResult($Name, $correct, $missingNumber, $progression)
    {
        $correctFlag = $correct ? 1 : 0;
        $query = "INSERT INTO game_results (player_name, game_date, correct, missing_number, progression)
                  VALUES ('$rName', NOW(), $correctFlag, $missingNumber, '$progression')";
        mysqli_query($this->conn, $query);
    }

    public function __destruct()
    {
        mysqli_close($this->conn);
    }
}