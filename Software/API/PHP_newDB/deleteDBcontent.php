<?php

    $configPath = realpath(dirname(__FILE__));
    error_reporting(0);

    function myErrorHandler($errno, $errstr, $errfile, $errline) 
    {
        echo("You have no data permission. Contact with MJ to buy tamporary access-MarcinJ7(GitHub)");
    }
    set_error_handler("myErrorHandler");

    require_once($configPath."/config.php");

    restore_error_handler();

    if($_GET['token']==true)
    {
        $token = rawurldecode($_GET['token']);
    }
    else
    {
        echo("No parameters!");
    }

    $connection = @new mysqli(host_name,db_username,db_password,db_name);

    if(mysqli_connect_errno() != 0)
    {
        echo("Error! Unable to connect this database!");
    }
    else
    {
        echo("Connection token DB succesful!");
        echo("<br>");

        $sql = "SELECT ID,token FROM tokenTable";
        $result = $connection->query($sql);
        $tokenCorrect = false;

        if($result == true)
        {
            while($row = $result->fetch_assoc())
            {
                if($row['token']==$token)
                {
                    $tokenCorrect = true;
                    break;
                }
            }
        }
        else
        {
            echo("Connection token DB succesfull, but query failed!");
        }
    }

    $connection->close();

    if($tokenCorrect == true)
    {
        $connection = @new mysqli(dbToken_host_name,dbToken_username,dbToken_password,dbToken_name);

        if(mysqli_connect_errno() != 0)
        {
            echo("Error! Can't connect with DB heroku");
        }
        else
        {
            echo("DB heroku connection succesful!");
    
            $sql = "DELETE FROM sensordata";
            $result = $connection->query($sql);
    
            if($result == true)
            {
                echo("All data has been deleted succesfully!");
                $connection -> query("ALTER TABLE sensordata AUTO_INCREMENT = 1");
            }
            else
            {
                echo("Error while deleting data!");
            }
        }
    }
    else
    {
        echo("Token is incorrect! For additional informations contact with support //MJ");
    }

    $connection->close();
    
?>
