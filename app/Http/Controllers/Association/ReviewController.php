<?php

namespace App\Http\Controllers\Association;

use Exception;
use App\Http\Requests\ReviewValidatedRequest;
use App\Models\Orphan;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class ReviewController extends Controller
{


    public function create($orphanId){
        $orphan = Orphan::where('id' , $orphanId)->first(['id' , 'name']);


        if (Gate::allows('complete-orphan-data', $orphan)){

            abort(404);

        }


        return view('review' , compact('orphan'));

    }


    public function researcherReview(ReviewValidatedRequest $request){

        $orphan = Orphan::findOrFail($request->orphan_id);

        if (Gate::allows('complete-orphan-data', $orphan)){

            abort(404);

        }


        if($orphan->isAuditor()){
            abort(404);
        }


        $request->merge([
            'review_number' => 'first',
            'review_date' =>now()->format('Y-m-d'),
        ]);

        $validated = $request->validated();



        DB::beginTransaction();

        try {


            Review::create($validated);

            // event(new OrphanReviewed($review));


            if ($request->status == 'approved') {

                $orphan->update(['role' => \App\Enums\OrphanRole::AUDITOR->value]);

            } else {
                $orphan->update(['role' => \App\Enums\OrphanRole::REJECTED->value]);
            }


            DB::commit();

            return redirect()->route('researcher.orphan.index')->with('success', __('تمت مراجعة اليتيم مراجعة أولية بنجاح'));


        }catch(Exception $e){
            DB::rollBack();
            return redirect()->back()->with('danger', __(' فشل في تسجيل بيانات مراجعة اليتيم. يرجى المحاولة مرة أخرى. '));

        }

    }

    public function associationReview(ReviewValidatedRequest $request){

        $request->merge([
            'review_number' => 'final',
            'review_date' =>now()->format('Y-m-d'),
        ]);

        $validated = $request->validated();


        DB::beginTransaction();

        try {

            $orphan = Orphan::findOrFail($validated['orphan_id']);

            Review::create($validated);

            // event(new OrphanReviewed($review));


            if ($request->status == 'approved') {

                if($orphan->activeSponsorships){
                    $orphan->update(['role' => \App\Enums\OrphanRole::SPONSORED->value]);
                }else{
                    $orphan->update(['role' => \App\Enums\OrphanRole::CERTIFIED->value]);
                }

            } else {
                $orphan->update(['role' => \App\Enums\OrphanRole::REJECTED->value]);
            }


            DB::commit();
            return redirect()->route('association.orphan.candidate')->with('success', __('تمت اعتماد اليتيم بنجاح'));


        }catch(Exception $e){
            DB::rollBack();
            return redirect()->back()->with('danger', __(' فشل في تسجيل بيانات مراجعة اليتيم. يرجى المحاولة مرة أخرى. '));

        }

    }
}
