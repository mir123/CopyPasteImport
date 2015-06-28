<?php

// This is CopyPasteImport v 0.1
// by mir (mir.rodriguez@greenpeace.org
// Code is cc-by-sa 3.0 -- Use it, remix it, share alike and give attribution

	function importer() {

        $jsonimports = $_POST['json'];
        $arrayimports = json_decode($jsonimports);

        // **Start validation

        $allgood = 0;
        $valmessage = "";
        $errmessage = "";
        $reqfields = ["names", "nationality", "date_of_birth", "place_of_birth", "passport_number", "defaultrankid"];

        // Validate required fields
        // Eventually to be replaced by call to database table with import fields

        foreach ($reqfields as $field) {
            if (array_key_exists($field, $arrayimports[0])) {
				$allgood .= 1;
                $valmessage .= "Required fields all there / ";
            } else {
                $errmessage = "Missing required field(s)"
            }
        }

       // validate date of birth

        $dateregex = "/^[0-9]{4}-(((0[13578]|(10|12))-(0[1-9]|[1-2][0-9]|3[0-1]))|(02-(0[1-9]|[1-2][0-9]))|((0[469]|11)-(0[1-9]|[1-2][0-9]|30)))$/";

        if (array_key_exists('date_of_birth', $arrayimports[0])) {

            $datecheck = $this->validate_filter($arrayimports, $dateregex, 'date_of_birth');
            if ($datecheck[0] == 1) {
                $valmessage .= "Dates validated / ";
            } else {
                $allgood += 1;
                $errmessage .= "Date error: " . $datecheck[1] . "\n";
            }
        }

        // validate email. the /i at the end of regex means case-insensitive

        $emailregex = "/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,6}$/i";

        if (array_key_exists('email_address', $arrayimports[0])) {

            $emailcheck = $this->validate_filter($arrayimports, $emailregex, 'email_address');
            if ($emailcheck[0] == 1) {
                $valmessage .= "Emails validated / ";
            } else {
                $allgood += 1;
                $errmessage .= "Email error: " . $emailcheck[1] . "\n ";
            }
        }

		// **End validation

		// Check if there were validation errors, and send error out, otherwise write to database

        if ($allgood > 0) {

            $validation = json_encode(array(
                'status' => 'error',
                'message' => $errmessage
            ));
        } 

		else {

        // Write to database. This is written for Codeigniter, put here your database code

            $i = 0;
            $total = 0;
            foreach ($arrayimports as $row) {
                $query = $this->db->insert('personaldata', $row);
                $affrows[$i] = $this->db->affected_rows();

                $i++;
            }

            foreach ($affrows as $j) {
                $total += $j;
            }

            if ($i != $j) {
                $validation = json_encode(array(
                    'status' => 'error',
                    'message' => "There was an error importing to the database. Check your data and try again"
                ));
            }

            $validation = json_encode(array(
                'status' => 'success',
                'message' => "Total rows inserted: " . $total
            ));
        }

    
    function validate_filter($arraydata, $filter, $field) {
        $i = 0;
        foreach ($arraydata as $row) {
            $value = $row->$field;
            if ($value != "") {
                if (preg_match($filter, $value)) {
                    
                } else {
                    return array(0, $value);
                }
            }
            $i++;
        }
        return array(1, true);
    }


?>
