<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\AcadYear;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Models\Program;

class ReportResultController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(){
        $programs = Program::where('programStat', 1)->get();
        return view('panel.report.report-result')
            ->with('programs', $programs);
    }

    public function index_data(Request $req){
        $sortkey = explode(".",$req->sort_by);

         $data = DB::table('users as a')
            ->select('a.user_id', 'a.lname', 'a.fname', 'a.mname', 'a.sex', 'a.first_program_choice', 'a.second_program_choice',
            DB::raw('coalesce((select sum(dd.score) from answer_sheets as aa
                join answers as bb on aa.answer_sheet_id = bb.answer_sheet_id
                join options as cc on bb.option_id = cc.option_id
                join questions as dd on cc.question_id = dd.question_id
                where aa.user_id = a.user_id and aa.section_id = 1 and cc.is_answer = 1 and aa.code = (select code from acad_years where active = 1)),0) as abstraction'),
            DB::raw('coalesce((select sum(dd.score) from answer_sheets as aa
                join answers as bb on aa.answer_sheet_id = bb.answer_sheet_id
                join options as cc on bb.option_id = cc.option_id
                join questions as dd on cc.question_id = dd.question_id
                where aa.user_id = a.user_id and aa.section_id = 2 and cc.is_answer = 1 and aa.code = (select code from acad_years where active = 1)),0) as logical'),
            DB::raw('coalesce((select sum(dd.score) from answer_sheets as aa
                join answers as bb on aa.answer_sheet_id = bb.answer_sheet_id
                join options as cc on bb.option_id = cc.option_id
                join questions as dd on cc.question_id = dd.question_id
                where aa.user_id = a.user_id and aa.section_id = 3 and cc.is_answer = 1 and aa.code = (select code from acad_years where active = 1)), 0) as english'),
            DB::raw('coalesce((select sum(dd.score) from answer_sheets as aa
                join answers as bb on aa.answer_sheet_id = bb.answer_sheet_id
                join options as cc on bb.option_id = cc.option_id
                join questions as dd on cc.question_id = dd.question_id
                where aa.user_id = a.user_id and aa.section_id = 4 and cc.is_answer = 1 and aa.code = (select code from acad_years where active = 1)), 0) as numerical'),
            DB::raw('coalesce((select sum(dd.score) from answer_sheets as aa
                join answers as bb on aa.answer_sheet_id = bb.answer_sheet_id
                join options as cc on bb.option_id = cc.option_id
                join questions as dd on cc.question_id = dd.question_id
                where aa.user_id = a.user_id and aa.section_id = 5 and cc.is_answer = 1 and aa.code = (select code from acad_years where active = 1)), 0) as general'),
            DB::raw('(select abstraction) + (select logical) + (select english) + (select numerical) + (select general) as total')
            )
            ->where('a.lname', 'like', $req->lname . '%')
            ->where('a.fname', 'like', $req->fname . '%')
            ->where('a.first_program_choice', 'like', $req->first_program . '%')
            ->paginate($req->perpage);

        return $data;
       // return DB::select('call proc_studentlist_result(?)', array($searchkey));
            //->paginate($req->perpage);

    }

    public function reportExcel(Request $req){
        $data = DB::table('users as a')
            ->select('a.user_id', 'a.lname', 'a.fname', 'a.mname', 'a.sex', 'a.first_program_choice', 'a.second_program_choice',
                DB::raw('coalesce((select sum(dd.score) from answer_sheets as aa
                join answers as bb on aa.answer_sheet_id = bb.answer_sheet_id
                join options as cc on bb.option_id = cc.option_id
                join questions as dd on cc.question_id = dd.question_id
                where aa.user_id = a.user_id and aa.section_id = 1 and cc.is_answer = 1 and aa.code = (select code from acad_years where active = 1)),0) as abstraction'),
                DB::raw('coalesce((select sum(dd.score) from answer_sheets as aa
                join answers as bb on aa.answer_sheet_id = bb.answer_sheet_id
                join options as cc on bb.option_id = cc.option_id
                join questions as dd on cc.question_id = dd.question_id
                where aa.user_id = a.user_id and aa.section_id = 2 and cc.is_answer = 1 and aa.code = (select code from acad_years where active = 1)),0) as logical'),
                DB::raw('coalesce((select sum(dd.score) from answer_sheets as aa
                join answers as bb on aa.answer_sheet_id = bb.answer_sheet_id
                join options as cc on bb.option_id = cc.option_id
                join questions as dd on cc.question_id = dd.question_id
                where aa.user_id = a.user_id and aa.section_id = 3 and cc.is_answer = 1 and aa.code = (select code from acad_years where active = 1)), 0) as english'),
                DB::raw('coalesce((select sum(dd.score) from answer_sheets as aa
                join answers as bb on aa.answer_sheet_id = bb.answer_sheet_id
                join options as cc on bb.option_id = cc.option_id
                join questions as dd on cc.question_id = dd.question_id
                where aa.user_id = a.user_id and aa.section_id = 4 and cc.is_answer = 1 and aa.code = (select code from acad_years where active = 1)), 0) as numerical'),
                DB::raw('coalesce((select sum(dd.score) from answer_sheets as aa
                join answers as bb on aa.answer_sheet_id = bb.answer_sheet_id
                join options as cc on bb.option_id = cc.option_id
                join questions as dd on cc.question_id = dd.question_id
                where aa.user_id = a.user_id and aa.section_id = 5 and cc.is_answer = 1 and aa.code = (select code from acad_years where active = 1)), 0) as general'),
                DB::raw('(select abstraction) + (select logical) + (select english) + (select numerical) + (select general) as total')
            )
            ->where('a.first_program_choice', 'like', $req->first_program . '%')
            ->get();
        return $data;
    }

}
