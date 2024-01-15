<?php

    function confirmQuery($string){

        global $conn;

        if(!$string){

            die("ERROR" . mysqli_error($conn));
        }
    }

    function escape($string){

        global $conn;

        return mysqli_real_escape_string($conn, trim($string));

    }

    function InsertLogs($id, $conn, $val1, $val2, $val3){
        $insert = "INSERT INTO scraplogs_tbl(ScrapDetailID, SRPlate, SRPaste, Trimmings) ";
        $insert .= "VALUES('".$id."','".$val1."', '".$val2."', '".$val3."') ";
        $result_query = odbc_exec($conn, $insert);

        if($result_query){
            $val =  2;
        }
        else{
            $val =  0;
        }
    }

    function Login($username, $password, $conn){
        $result = 0;
        $select = "SELECT cred.EmployeeID, emp.FirstName, emp.LastName, emp.NickName, cred.UserName, cred.Password  ";
        $select .= "FROM usercredential_tbl cred ";
        $select .="JOIN employee_tbl emp ON cred.EmployeeID = emp.EmployeeID ";
        $select .="WHERE cred.UserName = '".$username."' and cred.Password = '".$password."' ";
        $select .="and cred.IsActive = 1 and cred.IsDeleted = 0 ";

        $result = odbc_exec($conn, $select);

        $count = odbc_num_rows($result);

        if($count !=0){
            while($row = odbc_fetch_array($result)){
                setcookie("GPAdmin_employeeID",$row['EmployeeID'],time()+3600 * 24 * 365, '/');
                setcookie("GPAdmin_FirstName",$row['FirstName'],time()+3600 * 24 * 365, '/');
                setcookie("GPAdmin_LasttName",$row['LastName'],time()+3600 * 24 * 365, '/');
                setcookie("GPAdmin_NickName",$row['NickName'],time()+3600 * 24 * 365, '/');

                setcookie("GPAdmin_UserName",$row['UserName'],time()+3600 * 24 * 365, '/');
                setcookie("GPAdmin_Password",$row['Password'],time()+3600 * 24 * 365, '/');
            }

            return 1;
        }
        else{
            return 0;
        }

    }

    function Logout(){

        session_name('sessionGPAdmin');
        session_start();
        session_destroy();
        $page=$_SERVER['PHP_SELF'];
        $sec = "10";
        header('Refresh:1');


        setcookie("GPAdmin_employeeID","",time()-3600 * 24 * 365, '/');
        setcookie("GPAdmin_FirstName","",time()-3600 * 24 * 365, '/');
        setcookie("GPAdmin_LasttName","",time()-3600 * 24 * 365, '/');
        setcookie("GPAdmin_NickName","",time()-3600 * 24 * 365, '/');

        setcookie("GPAdmin_UserName","",time()-3600 * 24 * 365, '/');
        setcookie("GPAdmin_Password","",time()-3600 * 24 * 365, '/');

        if(setcookie("GPAdmin_employeeID","",time()-3600 * 24 * 365, '/')){
            return 1;
        }
        else{
            return 0;
        }
    }

?>