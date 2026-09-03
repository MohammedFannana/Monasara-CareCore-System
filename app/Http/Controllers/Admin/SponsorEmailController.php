<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SendSponsorEmailValidatedRequest;
use App\Models\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendSponsorEmail;

class SponsorEmailController extends Controller
{
    //// عرض صفحة الإرسال
    public function index()
    {
        return view('admins.sponsors.email');
    }

    // إرسال البريد لجميع الكفلاء
    // public function send(Request $request)
    // {
    //     $request->validate([
    //         'subject' => 'required|string|max:255',
    //         'message' => 'required|string',
    //     ]);

    //     $sponsors = Sponsor::get();

    //     foreach ($sponsors as $sponsor) {
    //         $emails = preg_split('/[\s,;]+/', trim($sponsor->email));

    //         foreach ($emails as $email) {
    //             if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //                 Mail::raw($request->message, function ($mail) use ($email, $request) {
    //                     $mail->to($email)
    //                         ->subject($request->subject);
    //                 });
    //             }
    //         }
    //     }


    //     return back()->with('success', 'تم إرسال الإيميلات لجميع الكفلاء بنجاح ✅');
    // }

    // public function send(Request $request)
    // {
    //     $request->validate([
    //         'subject' => 'required|string|max:255',
    //         'message' => 'required|string',
    //     ]);

    //     $sponsors = Sponsor::all();
    //     $sentCount = 0;
    //     $failed = [];

    //     foreach ($sponsors as $sponsor) {
    //         $emails = array_filter(array_map('trim', preg_split('/[,\s;]+/', $sponsor->email)));

    //         foreach ($emails as $email) {
    //             if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //                 try {
    //                     Mail::raw($request->message, function ($mail) use ($email, $request) {
    //                         $mail->to($email)
    //                             ->subject($request->subject)
    //                             ->from(config('mail.from.address'), config('mail.from.name'));
    //                     });
    //                     $sentCount++;
    //                     usleep(300000); // تأخير بسيط بين كل رسالة
    //                 } catch (\Throwable $e) {
    //                     Log::error("Mail send failed to {$email}: ".$e->getMessage());
    //                     $failed[] = $email;
    //                 }
    //             } else {
    //                 Log::warning("Invalid email skipped: {$email}");
    //             }
    //         }
    //     }

    //     return back()->with('success', "تم إرسال {$sentCount} رسالة بنجاح ✅")->with('failed', $failed);
    // }

    // في الـ Controller
    public function send(SendSponsorEmailValidatedRequest $request)
    {
        $request->validated();

        $sentCount = 0;

        // ✅ chunk() بدل all() لتجنب memory leak
        Sponsor::chunk(100, function ($sponsors) use ($request, &$sentCount) {
            foreach ($sponsors as $sponsor) {
                $emails = array_filter(
                    array_map('trim', preg_split('/[,\s;]+/', $sponsor->email))
                );

                foreach ($emails as $email) {
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        // ✅ dispatch للـ Queue بدل الإرسال المباشر
                        SendSponsorEmail::dispatch(
                            $email,
                            $request->subject,
                            $request->message
                        );
                        $sentCount++;
                    }
                }
            }
        });

        return back()->with('success', "تم إضافة {$sentCount} رسالة لقائمة الإرسال ✅");
    }
}
