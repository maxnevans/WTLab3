<?php

    if (!session_id()) session_start();
    if (empty($_POST['token']) || $_POST['token'] != $_SESSION['token']) die("Invalid token!");

    foreach($_POST as $key => $value)
    {
        $value = htmlspecialchars($value);
        $_POST[$key] = trim($value);
    }
    if (empty($_POST['birthday_date']))
        set_error("birthday_date", "Дата рождения некорретная");
    if (empty($_POST['days_will_be']) && $_POST['days_will_be'] != 0 || !is_numeric($_POST['days_will_be']) || $_POST['days_will_be'] < 0 || $_POST['days_will_be'] > PHP_INT_MAX)
        set_error("days_will_be", "Неверный формат, используйте целое неотрицательное число");

    if (empty($answer["error"]))
    {
        try 
        {
            $answer['age'] = get_exact_age($_POST['birthday_date'], (integer)$_POST['days_will_be']);
            $answer['year_east_name'] = get_year_east(DateTime::createFromFormat('Y-m-d', $_POST['birthday_date'])->format('Y'));
        }
        catch(Exception $e)
        {
            set_error("birthday_date", "Дата рождения некорретная");
            $answer['error']['description'] = $e;
        }
    }

    echo json_encode($answer);
    exit;


    function get_exact_age($birthday, $when_day)
    {
        $anchor_date = DateTime::createFromFormat("Y-m-d", $birthday);
        if (!$anchor_date) throw new Exception(DateTime::getLastErrors());
        $date_interval = new DateInterval("P".$when_day."D");
        $anchor_date->add($date_interval);
        return $anchor_date->format("m/d/Y");
    }

    function get_year_east($year)
    {
        $years = [
            "Крыса",
            "Бык",
            "Тигр",
            "Кролик",
            "Дракон",
            "Змея",
            "Лошадь",
            "Коза",
            "Обезьяна",
            "Петух",
            "Собака",
            "Свинья"
        ];
        return $years[(abs($year - 4)) % 12];
    }

    function set_error($field, $desc)
    {
        global $answer;
        $answer['error']['fields'][$field] = $desc;
    }