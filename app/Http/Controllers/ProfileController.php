<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ProfileLink;
use App\Http\Requests\UpdateProfileRequest;
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
        /** @var User|null $currentUser */
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
            ->withCount(['favorite_users', 'comments'])
            ->latest()
            ->paginate(12);

        $isFollowing = $currentUser?->isFollowing($user) ?? false;
        $hasPendingRequest = $currentUser?->hasPendingFollowRequest($user) ?? false;

        // フォロー申請一覧（自分自身のプロフィールのみ）
        $pendingFollowRequests = [];
        if ($currentUser && $currentUser->id === $user->id) {
            $user->refresh(); // 最新状態を取得
            $pendingFollowRequests = $user->pendingFollowRequests()->get();
        }

        return view('profile.show', compact(
            'user',
            'posts',
            'isFollowing',
            'hasPendingRequest',
            'pendingFollowRequests'
        ));
    }

    /**
     * トークンによるプロフィール表示
     */
    public function showByToken(string $token)
    {
        $profileLink = ProfileLink::where('token', $token)
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->first();

        if (!$profileLink) {
            abort(404, 'このリンクは無効または期限切れです');
        }

        $user = $profileLink->user;
        $user->loadCount(['followers', 'following', 'posts']);

        $posts = $user->posts()
            ->with('user', 'shop')
            ->withCount(['favorite_users', 'comments'])
            ->latest()
            ->paginate(12);

        /** @var User|null $currentUser */
        $currentUser = auth()->user();
        $isFollowing = $currentUser?->isFollowing($user) ?? false;
        $hasPendingRequest = $currentUser?->hasPendingFollowRequest($user) ?? false;

        return view('profile.show', compact(
            'user',
            'posts',
            'isFollowing',
            'hasPendingRequest'
        ))->with('isTokenAccess', true);
    }

    /**
     * プロフィール編集フォーム表示
     */
    public function edit()
    {
        /** @var User $user */
        $user = auth()->user();

        $posts = $user->posts()
            ->with('user', 'shop')
            ->latest()
            ->paginate(5);

        return view('profile.edit', compact('user', 'posts'));
    }

    /**
     * プロフィール更新
     */
    public function update(UpdateProfileRequest $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $validated = $request->validated();

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $this->handleAvatarUpload($request->file('avatar'), $user);
        }

        $user->update($validated);

        return redirect()->route('profile.show', $user)
            ->with('success', 'プロフィールを更新しました');
    }

    /**
     * プロフィール削除（Laravel Breezeデフォルト機能維持）
     */
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        /** @var User $user */
        $user = $request->user();

        auth()->logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * プロフィールリンク生成
     */
    public function generateProfileLink(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $profileLink = ProfileLink::generateForUser($user);

        return response()->json([
            'success' => true,
            'link' => route('profile.show-by-token', $profileLink->token),
            'expires_at' => $profileLink->expires_at->format('Y年n月j日 H:i')
        ]);
    }

    /**
     * アバター画像のアップロード処理
     */
    private function handleAvatarUpload($file, User $user): string
    {
        // 既存のローカルファイル名の場合のみ削除
        if ($user->avatar && !str_starts_with($user->avatar, 'http')) {
            Storage::disk('public')->delete('avatars/' . $user->avatar);
        }

        // Cloudinaryへアップロード
        $uploaded = \CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary::upload($file->getRealPath(), [
            'folder' => 'user_avatars',
            'transformation' => [
                'width' => 400,
                'height' => 400,
                'crop' => 'fill',
                'gravity' => 'face',
                'quality' => 'auto:good'
            ]
        ]);
        return $uploaded->getSecurePath(); // CloudinaryのURLをそのまま保存
    }
}
