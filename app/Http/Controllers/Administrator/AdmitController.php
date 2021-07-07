<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\AcadYear;
use Illuminate\Http\Request;


use App\Models\Gadtest;
use Illuminate\Support\Facades\Hash;

class AdmitController extends Controller
{
    //

    public function admitStudent(Request $req){
        $n = time() . $req->user_id;
        $studentCode = substr(md5($n), -6);

        $ay = AcadYear::where('active', 1)->first();
        //return $req->fields;

        //make obj(programs) in one string
        $programs = '';
        foreach ($req->programs as $row){
            $programs = $programs .  $row['CCode'] . ',';
        }

        $programs = substr_replace($programs, '', -1);
        $status = strtoupper($req->fields['status']);

        Gadtest::create([
            'StudLName' => strtoupper($req->fields['lname']),
            'StudFName' => strtoupper($req->fields['fname']),
            'StudMName' => strtoupper($req->fields['mname']),
            'StudSex' => strtoupper($req->fields['sex']),
            'StudClass' => $status,
            'StudBDate' => $req->fields['bdate'],
            'StudCNum' => $req->fields['contact_no'],
            'StudCourse' => strtoupper($programs),
            'StudYear' => $status == 'NEW' ? '1' : '0',
            'email' => $req->fields['email'],
            'term' => $ay->code,
            'StudLSBrgyCode' => $req->fields['barangay_id'],
            'StudPStr' => $req->fields['street'],
            'password' => Hash::make($studentCode),
            'rating' => $req->fields['total'],
            'learning_mode' => $req->fields['learning_mode'],
        ]);

        return $studentCode;

    }
}
