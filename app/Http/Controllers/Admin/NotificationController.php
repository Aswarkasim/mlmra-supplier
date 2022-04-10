<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ActionType;
use App\Enums\CategoryType;
use App\Enums\MediaType;
use App\Enums\NotificationType;
use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Notification;
use App\Traits\AdminGeneralTrait;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use AdminGeneralTrait;

    public function index() {
        $listNotification = Notification::orderByDesc('id')->paginate(15);
        return view('admin.notification.index', compact('listNotification'));
    }

    public function create() {
        $listNotificationType = NotificationType::getValues();
        return view('admin.notification.create', compact('listNotificationType'));
    }

    public function save(Request $request) {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'notification_type' => 'required',
            'thumbnail' => 'image'
        ]);
        $media = new Media();
        $notification = new Notification();
        if ($request->hasFile('thumbnail')) {
            $media = $this->uploadMedia($request->thumbnail, MediaType::IMAGE, CategoryType::NOTIFICATION, null);
        }
        $notification->title = $request->title;
        $notification->description = $request->description;
        $notification->notification_type = $request->notification_type;
        $notification->status = StatusType::DRAFT;
        $notification->media_id = $media->id;
        $notification->saveOrFail();
        \Brian2694\Toastr\Facades\Toastr::success('Berhasil ditambahkan:)','Success');
        return redirect(route('admin.notification'));

    }

    public function edit($id) {
        $listNotificationType = NotificationType::getValues();
        $notification = Notification::findOrFail($id);
        return view('admin.notification.edit', compact('listNotificationType', 'notification'));
    }

    public function update(Request $request, $id) {
        $notification = Notification::findOrFail($id);
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'notification_type' => 'required',
            'thumbnail' => 'image'
        ]);
        $media = $notification->media;
        if ($request->hasFile('thumbnail')) {
            $media = $this->uploadMedia($request->thumbnail, MediaType::IMAGE, CategoryType::NOTIFICATION, null);
        }
        $notification->media_id = $media ? $media->id : null;
        $notification->name = $request->title;
        $notification->notification_type = $request->notification_type;

        $actionType = ActionType::getInstance($request->action_type);

        if ($actionType->is(ActionType::PUBLISH)) {
            $notification->status = StatusType::PUBLISHED;
            $notification->save();
            \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
            return redirect(route('admin.category'))->with(['success' => 'Notifikasi Diperbaharui!']);
        } else if ($actionType->is(ActionType::ARCHIVE)) {
            $notification->status = StatusType::ARCHIVED;
            $notification->save();
            \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
            return redirect(route('admin.category'))->with(['success' => 'Notifikasi Diperbaharui!']);
        }

        $notification->status = StatusType::DRAFT;
        $notification->save();
        \Brian2694\Toastr\Facades\Toastr::success('Berhasil diupdate:)','Success');
        return redirect(route('admin.notification.edit', $notification->id))->with(['success' => 'Notifikasi Diperbaharui!']);
    }
}
