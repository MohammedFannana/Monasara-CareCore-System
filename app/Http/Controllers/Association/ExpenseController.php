<?php

namespace App\Http\Controllers\Association;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpenseValidatedRequest;
use App\Http\Requests\UpdateExpenseValidatedRequest;
use App\Models\Expense;
use App\Models\Orphan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $association = auth('association')->user();

        $expenses = Expense::whereHas('orphan', function ($query) use ($association, $request) {
                // أولاً: التأكد من أن اليتيم يتبع لهذه الجمعية
                $query->where('association_id', $association->id);

                // ثانياً: إذا كان هناك بحث، ابحث في اسم اليتيم
                if ($request->filled('search')) {
                    $query->where('name', 'LIKE', "%{$request->search}%");
                }
            })
            ->latest()
            ->paginate(15)
            ->withQueryString(); // مهم جداً للحفاظ على كلمة البحث عند التنقل بين الصفحات

        return view('associations.expenses.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        if (auth('association')->check()) {

            $orphans = Orphan::where('association_id', auth('association')->id())
                ->sponsored()
                ->get(['id', 'name', 'id_number']);

        } elseif (auth('researcher')->check()) {

            $orphans = Orphan::where('association_id', auth('researcher')->user()->association_id)
                ->sponsored()
                ->get(['id', 'name', 'id_number']);

        }


        return view('associations.expenses.create' , compact('orphans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseValidatedRequest $request)
    {


        $validated = $request->validated();
        $validated['status'] = "pending ";

        $orphan_name = $orphan_name = Orphan::find($validated['orphan_id'])?->name;


         $fields = [
            'payment_received',
            'delivery_bail',
            'thank_letter_audio'
        ];

        foreach ($fields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $path = $file->store("images/expenses/{$orphan_name}", 'public');
                $validated[$field] = $path;
            }
        }

        Expense::create($validated);
        return redirect()->route('association.expenses.index')->with('success' , 'تم إضافة بيانات الدفع بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }


    public function edit(Expense $expense){

        $associationId = auth('association')->id();

        // تحميل علاقة اليتيم إذا لم تكن محمّلة
        $expense->loadMissing('orphan');

        // التحقق من أن اليتيم تابع للجمعية
        if (!$expense->orphan || $expense->orphan->association_id !== $associationId) {
            abort(403, 'غير مسموح لك بتعديل هذا المصروف.');
        }

        $orphans = Orphan::where('association_id' , auth('association')->id())
        ->sponsored()
        ->get(['id' , 'name' , 'id_number']);
        return view('associations.expenses.edit' , compact(['expense' , 'orphans']));
    }

    public function update(UpdateExpenseValidatedRequest $request , Expense $expense){

         $associationId = auth('association')->id();

        // تحميل علاقة اليتيم إذا لم تكن محمّلة
        $expense->loadMissing('orphan');

        // التحقق من أن اليتيم تابع للجمعية
        if (!$expense->orphan || $expense->orphan->association_id !== $associationId) {
            abort(403, 'غير مسموح لك بتعديل هذا المصروف.');
        }

        // التحقق من صحة البيانات
        $validated = $request->validated();

        $validated['orphan_id'] = $expense->orphan_id;

        // اسم اليتيم
        $orphan_name = Orphan::find($validated['orphan_id'])?->name;

        // الحقول الخاصة بالملفات
        $fields = [
            'payment_received',
            'delivery_bail',
            'thank_letter_audio'
        ];

        // جلب النموذج القديم من قاعدة البيانات
        // $oldExpense = Expense::findOrFail($expense->id);

        $filesToDelete = [];

        // أولاً: رفع الملفات وتخزين المسارات في validated، وتجهيز الملفات للحذف لاحقًا
        foreach ($fields as $field) {
            if ($request->hasFile($field)) {
                // رفع الجديد
                $file = $request->file($field);
                $path = $file->store("images/expenses/{$orphan_name}", 'public');
                $validated[$field] = $path;

                // تخزين القديم في قائمة الحذف لاحقًا
                if ($expense->$field && Storage::disk('public')->exists($expense->$field)) {
                    $filesToDelete[] = $expense->$field;
                }
            }
        }

        // تحديث البيانات
        $expense->update($validated);

        // بعد نجاح التحديث فقط: حذف الملفات القديمة
        foreach ($filesToDelete as $filePath) {
            Storage::disk('public')->delete($filePath);
        }


        return redirect()->route('association.expenses.index')->with('success' , 'تم تحديث بيانات الدفع بنجاح');


    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {

        $expense->delete();
        return redirect()->route('association.expenses.index')->with('success' , 'تم حذف بيانات الدفع بنجاح');


    }

    public function makeActive(Request $request){
        // dd('c');
        $orphanIds = explode(',', $request->orphan_ids);

        $orphans = Expense::whereIn('id', $orphanIds)->get();


        if ($orphans->isEmpty()) {
            return back()->with('danger', 'لا يوجد أيتام متاحين');
        }


        Expense::whereIn('id', $orphanIds)->update(['status' => 'active']);

        return redirect()->route('association.expenses.index')->with('success', 'تم تحديث حالة الكشوفات بنجاح');
    }
}
