<?php

namespace App\Http\Controllers;

// use App\Http\Requests\StoreOrphanRequest;

use Exception;
use App\Models\Orphan;
use App\Models\Association;
use Illuminate\Support\Arr;
use App\Models\ExternalLink;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Requests\StoreOrphanValidatedRequest;
use App\Http\Requests\UpdateOrphanValidatedRequest;
use App\Http\Requests\StoreOrphanProfileValidatedRequest;


class OrphanController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        // $orphan = Auth::guard('orphan')->user()->load('profile');
        $orphan = Auth::guard('orphan')->user();
        if (!$orphan) {
            return redirect()->route('login')->with('error', 'الرجاء تسجيل الدخول أولاً');
        }

        $orphan = $orphan->loadMissing(['sibling', 'profile', 'activeSponsorships.sponsor']);

        if (Gate::allows('complete-orphan-data', $orphan)) {
            // return view('orphans.index' ,compact('orphan'));
            return redirect()->route('complete.profile' , $orphan->id);
        }

        return view('orphans.index' ,compact('orphan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

        if (Auth::guard('association')->check()) {
            $association = Auth::guard('association')->user();
        }
        else {
            abort(403, 'غير مصرح بالدخول لهذه الصفحة');
        }

        return view('orphans.create', compact('association'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrphanValidatedRequest $request)
    {
        $auth_association = Auth::guard('association');


        if($auth_association->check()){

            $request->merge([
                'association_id' =>$auth_association->user()->id,
                'role' => \App\Enums\OrphanRole::CERTIFIED->value
            ]);

        }elseif(Auth::guard('researcher')->check()){
            $association= Auth::guard('researcher')->user()->association_id;

            $request->merge([
                'association_id' =>$association,
                'role' => \App\Enums\OrphanRole::AUDITOR->value
            ]);

            // dd($request);

        }elseif (Auth::guard('web')->check()) {

            $request->merge(['role' => \App\Enums\OrphanRole::CERTIFIED->value]);

        }else{
            abort(403, 'غير مصرح بالدخول لهذه الصفحة');
        }

        $validated = $request->validated();



        // dd(intval($validated['id_number']));

        $validated['password'] = Hash::make(intval($validated['id_number']));
        // dd($validated);
        // $validated = $request->validate();

        $fields = [
            'image',
            'father_death_certificate',
            'mother_death_certificate',
            'medical_report',
            'educational_certificate',
            'wallet_owner_id_number_image',
        ];

        foreach ($fields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $path = $file->store("images/orphans/{$request->name}", 'public');
                $validated[$field] = $path;
            }
        }


        // dd($validated);
        DB::beginTransaction();

        try {

            // start store in orphans table
            $validatedData = Arr::only($validated , ['image' , 'role' ,'name' , 'association_id' , 'birth_date' , 'birth_place' , 'country',
                                                        'city' , 'landmark' , 'id_number' ,'orphan_status' , 'gender' , 'nominating_authority',
                                                        'mother_name' , 'death_mother_date' , 'cause_mother_death', 'father_name', 'death_father_date', 'cause_father_death', 'mother_id_number', 'mother_marital_status',
                                                        'mother_phone', 'father_id_number', 'father_marital_status', 'father_phone'
                                                        , 'father_death_certificate', 'not_available_father_death', 'mother_death_certificate', 'not_available_mother_death' , 'guardian_name' ,'guardian_relation',
                                                        'guardian_jop' , 'password'
                                                    ]);


            // dd($validatedData);
            $orphan = Orphan::create($validatedData);


            // store in profiles table
            $profileData = Arr::only($validated ,[
                'guardian_id_number' , 'guardian_housing' , 'guardian_whats_phone' , 'guardian_first_phone' ,
                'guardian_secound_phone' , 'health_status' ,'disease_type' ,'medical_report' ,'not_available_medical_report' ,
                'educational_status' , 'average' ,'academic_stage' ,'educational_certificate' ,'not_available_educational_certificate',
                'receive_guarantee' , 'account_number' ,'bank' , 'phone_number_linked_account' ,'wallet_number' , 'wallet_owner'
                ,'wallet_owner_id_number' ,'wallet_owner_id_number_image' , 'not_available_wallet_owner_id_number_image'

            ]);

            // dd($profileData);

            $orphan->profile()->create($profileData);



            // store in sbiling table
            // $siblingsData = Arr::only($validated ,[
            //     'brother_name' , 'brother_gender' ,'brother_age' , 'brother_marital_status' , 'brother_jop' , 'brother_id_number'
            // ]);

            // dd(count($request->brother_name));

            $orphan->sibling()->create([
                'male_number' => $request->male_number,
                'female_number' => $request->female_number,
            ]);

            // dd($siblingsData);

            // $orphan->siblings()->create($siblingsData);


            DB::commit();
            return redirect()->back()->with('success', __('تمت اضافة اليتيم بنجاح'));


        }catch(Exception $e){
            // dd($e->getMessage());
            DB::rollBack();
            return redirect()->back()->with('danger', __(' فشل في تسجيل اليتيم. يرجى المحاولة مرة أخرى. '));

        }



    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Orphan $orphan)
    {
        $orphan = $orphan->load('profile' , 'sibling');
        return view('orphans.edit' , compact('orphan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrphanValidatedRequest $request, Orphan $orphan)
    {

        $validated = $request->validated();

        $fields = [
            'image',
            'father_death_certificate',
            'mother_death_certificate',
            'medical_report',
            'educational_certificate',
            'wallet_owner_id_number_image',
        ];

        foreach ($fields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $path = $file->store("images/orphans/{$orphan->name}", 'public');
                $validated[$field] = $path;

                // حذف الصورة القديمة بعد الرفع
                $oldPath = $orphan->$field ?? $orphan->profile->$field ?? null;
                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
        }

        DB::beginTransaction();
        try {
            // تحديث orphan
             $validatedData = Arr::only($validated , ['image' , 'birth_place' , 'country','city' , 'landmark' ,'orphan_status' , 'gender' , 'nominating_authority',
                                                        'mother_name' , 'death_mother_date' , 'cause_mother_death', 'father_name', 'death_father_date', 'cause_father_death', 'mother_id_number', 'mother_marital_status',
                                                        'mother_phone', 'father_id_number', 'father_marital_status', 'father_phone'
                                                        , 'father_death_certificate', 'not_available_father_death', 'mother_death_certificate', 'not_available_mother_death' , 'guardian_name' ,'guardian_relation',
                                                        'guardian_jop' , 'password'
                                                    ]);


            // dd($validatedData);
            $orphan->update($validatedData);


            // store in profiles table
            $profileData = Arr::only($validated ,[
                'guardian_id_number' , 'guardian_housing' , 'guardian_whats_phone' , 'guardian_first_phone' ,
                'guardian_secound_phone' , 'health_status' ,'disease_type' ,'medical_report' ,'not_available_medical_report' ,
                'educational_status' , 'average' ,'academic_stage' ,'educational_certificate' ,'not_available_educational_certificate',
                'receive_guarantee' , 'account_number' ,'bank' , 'phone_number_linked_account' ,'wallet_number' , 'wallet_owner'
                ,'wallet_owner_id_number' ,'wallet_owner_id_number_image' , 'not_available_wallet_owner_id_number_image'

            ]);

            // dd($profileData);

            $orphan->profile()->update($profileData);

            // تحديث الإخوة
            $orphan->sibling()->updateOrCreate(
                [
                    'orphan_id' => $orphan->id,
                ],
                [
                    'male_number' => $request->male_number,
                    'female_number' => $request->female_number,
                ]
            );


            DB::commit();
            return redirect()->back()->with('success', __('تم تحديث بيانات اليتيم بنجاح'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('danger', __('فشل في تحديث بيانات اليتيم.'))->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function completeProfile(string $id){

        $authOrphan = auth('orphan')->user();

        // 2. إذا البيانات مكتملة → امنع الدخول
        if (Gate::denies('complete-orphan-data', $authOrphan)) {
            abort(403, 'تم إكمال البيانات، لا يمكنك الوصول إلى صفحة الإكمال.');
        }

        if (!$authOrphan || $authOrphan->id != $id) {
            abort(403, 'غير مسموح لك بالوصول لهذا اليتيم');
        }

        $orphan = Orphan::findOrFail($id);
        return view('orphans.complete-profile' , compact('orphan'));

    }

    public function storeProfile(StoreOrphanProfileValidatedRequest $request , String $id){


        $authOrphan = auth('orphan')->user();

        // 2. إذا البيانات مكتملة → امنع الدخول
        if (Gate::denies('complete-orphan-data', $authOrphan)) {
            abort(403, 'تم إكمال البيانات، لا يمكنك الوصول إلى صفحة الإكمال.');
        }

        if (!$authOrphan || $authOrphan->id != $id) {
            abort(403, 'غير مسموح لك بالوصول لهذا اليتيم');
        }

        $orphan = Orphan::findOrFail($id);

        if($orphan->id_number != $authOrphan->id_number){
            abort(403, 'غير مسموح لك بتعديل هذا اليتيم');
        }

        $validated = $request->validated();


        $fields = [
            'image',
            'father_death_certificate',
            'mother_death_certificate',
            'medical_report',
            'educational_certificate',
            'wallet_owner_id_number_image',
        ];

        foreach ($fields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $folderName = Str::slug($orphan->name, '-');
                $path = $file->store("images/orphans/{$folderName}", 'public');
                $validated[$field] = $path;

            }
        }

        DB::beginTransaction();
        try {
            // تحديث orphan
            $orphan->update(Arr::only($validated, [
                'image',  'birth_place', 'country', 'city', 'landmark',
                 'orphan_status', 'gender', 'mother_name', 'death_mother_date', 'cause_mother_death',
                'father_name', 'death_father_date', 'cause_father_death', 'mother_id_number', 'mother_marital_status',
                'mother_phone', 'father_id_number', 'father_marital_status', 'father_phone',
                'father_death_certificate', 'not_available_father_death', 'mother_death_certificate',
                'not_available_mother_death', 'guardian_name', 'guardian_relation', 'guardian_jop'
            ]));

            // تحديث profile
            // if ($orphan->profile) {
            $orphan->profile()->create(Arr::only($validated, [
                'guardian_id_number', 'guardian_housing', 'guardian_whats_phone', 'guardian_first_phone',
                'guardian_secound_phone', 'health_status', 'disease_type', 'medical_report',
                'not_available_medical_report', 'educational_status', 'average', 'academic_stage',
                'educational_certificate', 'not_available_educational_certificate', 'receive_guarantee',
                'account_number', 'bank', 'phone_number_linked_account', 'wallet_number', 'wallet_owner',
                'wallet_owner_id_number', 'wallet_owner_id_number_image', 'not_available_wallet_owner_id_number_image'
            ]));
            // }

            // تحديث الإخوة
            $orphan->sibling()->create([
                'male_number' => $request->male_number,
                'female_number' => $request->female_number,
            ]);



            DB::commit();
            return redirect()->route('orphan.primary.index')->with('success', __('تم تحديث بيانات اليتيم بنجاح'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('danger', __('فشل في تسجيل اليتيم. يرجى المحاولة مرة أخرى.'));
        }

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function showImage(Request $request){
         try {
            $filePath = Crypt::decrypt($request->file);
            return view('orphans.show_image' , compact('filePath'));

        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function showVideo(Request $request){
        try {
            $filePath = Crypt::decrypt($request->file);
            return view('orphans.show_video' , compact('filePath'));

        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function showAudio(Request $request){
        try {
            $filePath = Crypt::decrypt($request->file);
            return view('orphans.show_audio' , compact('filePath'));

        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function balance(){

        $orphanUser = Auth::guard('orphan')->user();
        $sponsor =  $orphanUser->activeSponsorships->sponsor;
        $expenses = $orphanUser->expenses()->orderBy('created_at', 'desc')->paginate(8);
        // $expenseAmount = $orphanUser->expenses()->sum('bail_amount');
         $expenseAmount = $orphanUser->expenses->sum(function ($expense) {
            return $expense->duration * $expense->bail_amount;
        });

        // dd($balances);

        return view('orphans.balance-view' , compact('expenses' , 'expenseAmount','sponsor'));
    }
}
