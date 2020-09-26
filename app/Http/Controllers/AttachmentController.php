<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AttachmentController extends Controller
{
    public function show(Request $request, $organization_slug, $blab_slug) 
    {

        if ($organization_slug == null || $blab_slug == null) {
            abort(404);
        }

        $organization = \App\Organization::where('slug', $organization_slug)->first();

        if (!$organization) {
            abort(404);
        }

        $library_item = \App\LibraryItem::where('slug', $blab_slug)->with('attachments.user')->first();

        if (!$library_item) {
            $attachment = \App\Attachment::where('slug', $blab_slug)->with('user')->first();
        } else {
            $attachment = $library_item->attachments[0];
        }

        if (!$attachment || $attachment->organization_id != $organization->id || !$attachment->is_public) {
            abort(404);
        }

        if ($attachment->path != null && $attachment->processed) {

            $last_updated = Carbon::parse($attachment->temporary_url_last_updated);

            if ($attachment->temporary_url_last_updated == null || $last_updated->diffInDays() > 5) {
                $attachment->temporary_url = Storage::temporaryUrl(
                    $attachment->path, now()->addDays(7)
                );

                if ($attachment->thumbnail_path != null) {
                    $attachment->thumbnail_temporary_url = Storage::temporaryUrl(
                        $attachment->thumbnail_path, now()->addDays(7)
                    ); 
                }
            }

            $attachment->save();
        }

        return view('message.index', ['attachment' => $attachment, 'organization_slug' =>  $organization_slug, 'blab_slug' => $blab_slug]);

    }
    
}
