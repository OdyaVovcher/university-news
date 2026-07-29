<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\Comment;


class NewsController extends Controller
{
    // Главная страница со списком всех опубликованных новостей
    public function index()
    {
        $posts = Post::with('category')
            ->where('is_published', true)
            ->latest()
            ->paginate(6);

        $categories = Category::all();

        return view('news.index', compact('posts', 'categories'));
    }

    // Просмотр конкретной новости
    public function show($slug)
    {
        $post = Post::with('category')
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return view('news.show', compact('post'));
    }

    // Новости конкретной категории
    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $posts = Post::where('category_id', $category->id)
            ->where('is_published', true)
            ->latest()
            ->paginate(6);

        $categories = Category::all();

        return view('news.category', compact('category', 'posts', 'categories'));
    }

    public function storeComment(Request $request, $postId)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
            'user_name' => auth()->check() ? 'nullable' : 'required|string|max:255',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        Comment::create([
            'post_id' => $postId,
            'user_id' => auth()->id(),
            'user_name' => auth()->check() ? auth()->user()->name : $request->user_name,
            'body' => $request->body,
            'parent_id' => $request->parent_id,
            'is_approved' => true,
            
        ]);

        return back()->withFragment('comments-section');
    }

    public function deleteComment(Comment $comment)
    {
        // Проверяем: текущий пользователь — автор комментария ИЛИ админ
        if (auth()->id() !== $comment->user_id && !auth()->user()->is_admin) {
            abort(403, 'У вас нет прав на удаление этого комментария.');
        }

        $comment->delete();

        return back()->withFragment('comments-section');
    }
}