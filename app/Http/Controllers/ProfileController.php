<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use App\Models\ProfileLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        // プライベートアカウントのアクセス制御
        $currentUser = auth()->user();

        if (
            $user->is_private &&
            $currentUser?->id !== $user->id &&
            !$currentUser?->isFollowing($user)
        ) {

            return view('profile.private', compact('user'));
        }

        // パフォーマンス最適化：必要なデータのみ取得
        $user->loadCount(['followers', 'following', 'posts']);

        $posts = $user->posts()
            ->with('user')
            ->latest()
            ->paginate(12);

        $isFollowing = $currentUser?->isFollowing($user) ?? false;
        $hasPendingRequest = $currentUser?->hasPendingFollowRequest($user) ?? false;

        return view('profile.show', compact(
            'user',
            'posts',
            'isFollowing',
            'hasPendingRequest'
        ));
    }

    public function showByToken(Request $request, $token)
    {
        $profileLink = ProfileLink::where('token', $token)
            ->with('user')
            ->first();

        if (!$profileLink || !$profileLink->isValid()) {
            abort(404, 'プロフィールリンクが見つからないか、期限切れです');
        }

        $user = $profileLink->user;
        $user->loadCount(['followers', 'following', 'posts']);

        return view('profile.show-by-link', compact('user', 'profileLink'));
    }

    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = auth()->user();
        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->handleAvatarUpload($request->file('avatar'), $user);
        }

        $user->update($data);

        return redirect()->route('profile.show', $user)
            ->with('success', 'プロフィールを更新しました');
    }

    public function generateProfileLink(Request $request)
    {
        $user = auth()->user();

        if (!$user->is_private) {
            return response()->json([
                'error' => 'プライベートアカウントのみプロフィールリンクを生成できます'
            ], 400);
        }

        $profileLink = ProfileLink::generateForUser($user);

        return response()->json([
            'success' => true,
            'link' => route('profile.show-by-token', $profileLink->token),
            'expires_at' => $profileLink->expires_at->format('Y-m-d H:i:s')
        ]);
    }

    private function handleAvatarUpload($file, User $user): string
    {
        // 古いアバターを削除
        if ($user->avatar) {
            Storage::disk('public')->delete('avatars/' . $user->avatar);
        }

        // 新しいファイル名の生成
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        // ファイル保存
        $file->storeAs('avatars', $filename, 'public');

        return $filename;
    }
}
