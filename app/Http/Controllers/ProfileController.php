<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ProfileLink;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * プロフィール表示
     */
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
            ->with('user', 'shop')
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

    /**
     * プロフィール編集フォーム表示
     */
    public function edit()
    {
        $user = auth()->user();

        // ★修正: $posts変数を追加★
        $posts = $user->posts()
            ->with('user', 'shop')
            ->latest()
            ->paginate(5);

        return view('profile.edit', compact('user', 'posts'));
    }

    // 他のメソッドは変更なし...

    /**
     * プロフィール更新
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        // 基本的なバリデーション
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:50'],
            'bio' => ['nullable', 'string', 'max:500'],
            'location' => ['nullable', 'string', 'max:100'],
            'website' => ['nullable', 'url', 'max:255'],
            'avatar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],
            'is_private' => ['boolean']
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $this->handleAvatarUpload($request->file('avatar'), $user);
        }

        $user->update($validated);

        return redirect()->route('profile.show', $user)
            ->with('success', 'プロフィールを更新しました');
    }

    /**
     * プロフィールリンク生成
     */
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

    /**
     * アバター画像のアップロード処理
     */
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
