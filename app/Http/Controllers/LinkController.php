<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use App\Http\Requests\StoreLinkRequest;
use App\Events\LinkCreated;
use App\Events\LinkUpdated;
use App\Events\LinkDeleted;
use App\Events\LinkShared;
use App\Notifications\LinkSharedNotification;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function index()
    {
        $links = auth()->user()->links()->with('category')->latest()->paginate(15);
        return view('links.index', compact('links'));
    }

    public function create()
    {
        $categories = auth()->user()->categories()->orderBy('name')->get();
        return view('links.create', compact('categories'));
    }

    public function store(StoreLinkRequest $request)
    {
        $link = auth()->user()->links()->create($request->validated());
        
        // Sync tags
        if ($request->has('tags')) {
            $tags = collect($request->tags)->map(function ($tagName) {
                return Tag::firstOrCreate(['name' => strtolower(trim($tagName))]);
            });
            $link->tags()->sync($tags->pluck('id'));
        }
        
        // Fire event
        LinkCreated::dispatch($link);
        
        return redirect()->route('links.index')->with('success', 'Link created successfully!');
    }

    public function show(Link $link)
    {
        $this->authorize('view', $link);
        $link->load('category', 'tags');
        return view('links.show', compact('link'));
    }

    public function edit(Link $link)
    {
        $this->authorize('update', $link);
        $categories = auth()->user()->categories()->orderBy('name')->get();
        return view('links.edit', compact('link', 'categories'));
    }

    public function update(StoreLinkRequest $request, Link $link)
    {
        $this->authorize('update', $link);
        $link->update($request->validated());
        
        // Sync tags
        if ($request->has('tags')) {
            $tags = collect($request->tags)->map(function ($tagName) {
                return Tag::firstOrCreate(['name' => strtolower(trim($tagName))]);
            });
            $link->tags()->sync($tags->pluck('id'));
        } else {
            $link->tags()->detach();
        }
        
        // Fire event
        LinkUpdated::dispatch($link);
        
        return redirect()->route('links.index')->with('success', 'Link updated successfully!');
    }

    public function destroy(Link $link)
    {
        $this->authorize('delete', $link);
        $link->delete();
        
        // Fire event
        LinkDeleted::dispatch($link);
        
        return redirect()->route('links.index')->with('success', 'Link deleted successfully!');
    }

    public function share(Request $request, Link $link)
    {
        $this->authorize('share', $link);
        
        $request->validate([
            'user_email' => 'required|exists:users,email',
            'permission' => 'required|in:read,write',
        ]);

        $user = User::where('email', $request->user_email)->first();
        
        // Don't share with owner or already shared users
        if ($user->id === $link->user_id || $link->sharedWith()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Cannot share with this user.');
        }

        $link->sharedWith()->attach($user->id, ['permission' => $request->permission]);
        
        // Fire event
        LinkShared::dispatch($link, $user, $request->permission);
        
        // Send notification
        $user->notify(new LinkSharedNotification($link, auth()->user(), $request->permission));
        
        return back()->with('success', "Link shared with {$user->name}!");
    }

    public function favorite(Link $link)
    {
        $this->authorize('view', $link);
        
        auth()->user()->favorites()->toggle($link->id);
        
        return back()->with('success', auth()->user()->favorites()->where('link_id', $link->id)->exists() 
            ? 'Added to favorites!' 
            : 'Removed from favorites!');
    }

    public function favorites()
    {
        $links = auth()->user()->favorites()->with('category', 'tags')->latest()->paginate(15);
        return view('links.favorites', compact('links'));
    }

    public function restore(Link $link)
    {
        $this->authorize('restore', $link);
        $link->restore();
        return redirect()->route('links.index')->with('success', 'Link restored successfully!');
    }

    public function forceDelete(Link $link)
    {
        $this->authorize('forceDelete', $link);
        $link->forceDelete();
        return redirect()->route('links.index')->with('success', 'Link permanently deleted!');
    }

    public function trashed()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }
        
        $links = Link::onlyTrashed()->with('category', 'tags')->latest()->paginate(15);
        return view('links.trashed', compact('links'));
    }
}
