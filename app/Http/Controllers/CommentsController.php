<?php

namespace App\Http\Controllers;

use App\ForumModel;
use App\CommentModel;

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
        'forum_id' => $user_id,
        'comment' => $comment
      ]);

      // Update the forum Comments
      $forumComments = ForumModel::find($forumId);
      $forumComments->comments = (Integer) $forumComments->comments + 1;
      $forumComments->save();

      // Send Back A JSON Response......
      $Response['data'] = $comment;
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
}
