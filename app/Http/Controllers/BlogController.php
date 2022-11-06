<?php

namespace App\Http\Controllers;
use App\Models\Blog;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function addBlogPost(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:2|max:50',
            'description' => 'required|string|min:5',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 422);
        }
        $blog = Blog::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
        ]);
        if($blog) {
            return response()->json(['status' => true, 'message' => 'Blog post added successfully', 'data' => $blog]);
        }
        return response()->json(['status' => false, 'message' => 'Failed to add blog post'], 400);
    }

    public function editBlogPost(Request $request, $id) {
        $blog = Blog::where('id', $id)->update([
            'title'=> $request->title,
            'slug' => Str::slug($request->title), 
            'description'=> $request->description
        ]);
        if($blog) {
            return response()->json(['status' => true, 'message' => 'Blog post updated successfully', 'data' => $blog]);
        }
        return response()->json(['status' => false, 'message' => 'Failed to update blog post'], 400);

    }

    public function deleteBlogPost(Request $request, $id) {
        $is_exist = Blog::find($id);
        if(!$is_exist) {
            return response()->json(['status' => false, 'message' => 'Blog post does not exist'], 400);
        }
        $delete = Blog::where('id', $id)->delete();
        if($delete) {
            return response()->json(['status' => true, 'message' => 'Blog post deleted']);
        }
        return response()->json(['status' => false, 'message' => 'Failed to add delete blog post']);
    }

    public function viewBlogPosts(Request $request) {
        $blog = Blog::latest()->get();
        return response()->json(['status' => true, 'message' => 'All blog posts', 'data' => $blog]);
    }

    public function viewBlogPost(Request $request, $id) {
        $blog = Blog::find($id);
        if($blog){
            return response()->json(['status' => true, 'message' => 'Blog retrieved successfully', 'data' => $blog]);
        }
        return response()->json(['status' => false, 'message' => 'Blog not found'], 400);
    }
}