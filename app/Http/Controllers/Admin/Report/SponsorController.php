<?php

namespace App\Http\Controllers\Admin\Report;

use App\Exports\FinancialReportExport;
use App\Exports\GiftReportExport;
use App\Exports\OrphanReportExport;
use App\Exports\SponsorReportExport;
use App\Exports\SponsorshipReportExport;
use App\Http\Controllers\Controller;
use App\Jobs\GenerateReportJob;
use App\Models\Association;
use App\Models\Gift;
use App\Models\Orphan;
use App\Models\Report;
use App\Models\Sponsor;
use App\Models\Sponsorship;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use niklasravnsborg\LaravelPdf\Facades\Pdf;




class SponsorController extends Controller
{

    public function index(Request $request){

        $reports = Report::where('type' , 'sponsor')
        ->when($this->associationReportId(), fn ($query, $associationId) => $query->where('association_id', $associationId))
        ->when($request->filled('search'), function ($query) use ($request) {
            $date = Carbon::parse($request->search);

            $query->whereYear('date', $date->year)
                ->whereMonth('date', $date->month);
        })->latest('created_at')->paginate(10);
        return view('admins.reports.sponsor' , compact('reports'));

    }

    public function indexSponsorship(Request $request){

        $reports = Report::where('type' , 'sponsorship')
        ->when($this->associationReportId(), fn ($query, $associationId) => $query->where('association_id', $associationId))
        ->when($request->filled('search'), function ($query) use ($request) {
            $date = Carbon::parse($request->search);

            $query->whereYear('date', $date->year)
                ->whereMonth('date', $date->month);
        })->latest('created_at')->paginate(10);
        return view('admins.reports.sponsorship' , compact('reports'));

    }

    public function indexOrphan(Request $request){

        $assoc  = Association::get(['id' , 'name']);
        $reports = Report::where('type' , 'orphan')
        ->when($this->associationReportId(), fn ($query, $associationId) => $query->where('association_id', $associationId))
        ->when($request->filled('search'), function ($query) use ($request) {
            $date = Carbon::parse($request->search);

            $query->whereYear('date', $date->year)
                ->whereMonth('date', $date->month);
        })->latest('created_at')->paginate(10);
        return view('admins.reports.orphan' , compact('reports' , 'assoc'));

    }

    // **
    public function indexGift(Request $request){

        $reports = Report::where('type' , 'gift')
        ->when($this->associationReportId(), fn ($query, $associationId) => $query->where('association_id', $associationId))
        ->when($request->filled('search'), function ($query) use ($request) {
            $date = Carbon::parse($request->search);

            $query->whereYear('date', $date->year)
                ->whereMonth('date', $date->month);
        })->latest('created_at')->paginate(10);
        return view('admins.reports.gift' , compact('reports'));

    }


    public function financial(Request $request){
        $reports = Report::where('type' , 'financial')
        ->when($this->associationReportId(), fn ($query, $associationId) => $query->where('association_id', $associationId))
        ->when($request->filled('search'), function ($query) use ($request) {
            $date = Carbon::parse($request->search);

            $query->whereYear('date', $date->year)
                ->whereMonth('date', $date->month);
        })->latest('created_at')->paginate(10);
        return view('admins.reports.financial' , compact('reports'));
    }

    // **
    public function ExcelReport(Request $request){
        $this->validateReportType($request->type);

        GenerateReportJob::dispatch(
            $request->type,
            $request->date,
            $request->date_to,
            $request->status,
            'excel',
            [
                'search_by' => $request->input('search_by', []),
                'condition' => $request->input('condition', []),
                'search_value' => $request->input('search_value', []),
            ],
            $this->associationReportId()
        );

        return redirect()->back()->with('success', 'تم بدء إنشاء التقرير في الخلفية، وسيتم تفعيله خلال دقائق قليلة.');
    }

    // **
    public function PdfReport(Request $request){
        $this->validateReportType($request->type);

        GenerateReportJob::dispatch(
            $request->type,
            $request->date,
            $request->date_to,
            $request->status,
            'pdf',
            [
                'search_by' => $request->input('search_by', []),
                'condition' => $request->input('condition', []),
                'search_value' => $request->input('search_value', []),
            ],
            $this->associationReportId()
        );

        return redirect()->back()->with('success', 'تم بدء إنشاء التقرير في الخلفية، وسيتم تفعيله خلال دقائق قليلة.');
    }

    public function download(string $id){

        $report = $this->reportQuery()->findOrFail($id);

        $dateFormatted = Carbon::parse($report->date)->format('m-Y');
        $extension = pathinfo($report->report, PATHINFO_EXTENSION);


        $fileName = "report_{$report->id}_{$dateFormatted}." .  $extension;


        return Storage::disk('public')->download($report->report, $fileName);


    }

    public function destroy(string $id){
        $report = $this->reportQuery()->findOrFail($id);

        $report->delete();

        if ($report->report && Storage::disk('public')->exists($report->report)) {
            Storage::disk('public')->delete($report->report);
        }

        return redirect()->back()->with('success', 'تم حذف التقرير بنجاح');


    }

    protected function reportQuery()
    {
        return Report::query()
            ->when($this->associationReportId(), fn ($query, $associationId) => $query->where('association_id', $associationId))
            ->when(auth('association')->check() && auth('association')->user()->role === 'association', fn ($query) => $query->where('type', 'orphan'));
    }

    protected function associationReportId(): ?int
    {
        if (!auth('association')->check()) {
            return null;
        }

        return auth('association')->user()->getReportAssociationId();
    }

    protected function validateReportType(string $type): void
    {
        if (auth('association')->check() && auth('association')->user()->role === 'association' && $type !== 'orphan') {
            abort(403);
        }
    }
}
