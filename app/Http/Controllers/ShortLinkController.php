<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShortLinkRequest;
use App\Models\ShortLink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShortLinkController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $links = ShortLink::where('user_id', $user->id)->get(['id','original_url','code','clicks','created_at']);

        return response()->json($links);
    }

    public function store(StoreShortLinkRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();

        $code = $data['custom_code'] ?? null;

        if (! $code) {
            $maxAttempts = 10;
            $attempt = 0;
            $length = 7;

            do {
                $code = $this->generateCode($length);
                $exists = ShortLink::where('code', $code)->exists();
                $attempt++;
            } while ($exists && $attempt < $maxAttempts);

            if ($exists) {
                // j'ajoute un ptit suffixe ici pour assurer l'unicité
                $code = $code . '-' . substr(uniqid(), -4);
            }
        }

        $link = ShortLink::create([
            'user_id' => $user->id,
            'original_url' => $data['original_url'],
            'code' => $code,
            'clicks' => 0,
        ]);

        return response()->json($link, 201);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $link = ShortLink::where('id', $id)->where('user_id', $user->id)->first();

        if (! $link) {
            return response()->json(['message' => 'Link not found'], 404);
        }

        $link->delete();

        return response()->json(['message' => 'Link deleted successfully']);
    }

    public function redirect($code)
    {
        $link = ShortLink::where('code', $code)->first();

        if (! $link) {
            abort(404);
        }

        $link->increment('clicks');

        return redirect()->away($link->original_url, 302);
    }

    /**
     * Generate a random code using allowed characters.
     */
    private function generateCode(int $length = 7): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_-';
        $pool = str_repeat($chars, (int) ceil($length / strlen($chars)));
        return substr(str_shuffle($pool), 0, $length);
    }
}
