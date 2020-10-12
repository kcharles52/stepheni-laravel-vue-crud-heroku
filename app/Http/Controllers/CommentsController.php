<?php

namespace App\Http\Controllers;

use App\Forum as ForumModel;
use App\Comment as CommentModel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentsController extends Controller
{
  public function create(Request $Request, $forumId)
  {
    $Response = array();
    $user_id = $Request->user()->id;
    $forumId = stripcslashes(strip_tags($forumId));
    $comment = $Request->input('comment');

    try {
      $comment = CommentModel::create([
        'user_id' => $user_id,
        'forum_id' => $forumId,
        'comment' => $comment
      ]);

      // Update the forum Comments
      $forumComments = ForumModel::find($forumId);
      $forumComments->comments = (Integer) $forumComments->comments + 1;
      $forumComments->save();

      // Send Back A JSON Response......
      $Response['data'] = $comment;
      $Response['data']['user'] = $comment->user;
      $Response['humanTime'] = $comment->created_at->diffForHumans();
      $Response['status'] = 201;
      $Response['errors'] = '';
      $Response['message'] = 'Comment Has Been Saved Successfully.';

      return response()->json($Response, 201);
    } catch (Exception $e) {
      // send back the error message.........
      $Response['data'] = $Forum;
      $Response['status'] = 500;
      $Response['errors'] = array(
        'title' => 'Sorry, An Unexpected Error Occurred And Your Request Could Not Be Completed.',
        'message' => $e->getMessage()
      );
      $Response['message'] = '';
      return response()->json($Response, 500);
    }
  }

  public function fetchComments(Request $Request, $forumId)
  {
    $Response = array();
    // Fetch all comments under this forum...
    $comments = CommentModel::where('forum_id', $forumId)->orderBy('id', 'desc')->get();
    if (count($comments) > 0) {
      $comments = collect($comments)->map(function ($el) {
        $el->user = $el->user;
        $el->humanTime = $el->created_at->diffForHumans();
        return $el;
      });

      $Response['status'] = 200;
      $Response['comments'] = $comments;
      $Response['message'] = 'Successfully fetched ' . count($comments) . ' comments';
      $Response['errors'] = [];

      return response()->json($Response, 200);
    }

    return response()->json($Response, 204);
  }
}
