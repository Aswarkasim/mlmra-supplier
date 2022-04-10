<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusType;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request) {
        $search = $request->get('q');
        $listComment = Comment::with(['product', 'reseller'])->where('comment', 'like', "%".$search."%")
            ->orWhere('status', 'like', "%".$search."%")->orderByDesc('id')->paginate(15);
        return view('admin.comment.index', compact('listComment'));
    }

    public function block($id) {
        $comment = Comment::findOrFail($id);
        $comment->status = StatusType::BLOCKED;
        $comment->saveOrFail();
        \Brian2694\Toastr\Facades\Toastr::success('Berhasil diblock:)','Success');
        return redirect(route('admin.comment'));
    }
}
