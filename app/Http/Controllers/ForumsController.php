<?php

namespace App\Http\Controllers;

use App\Forum as ForumModel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ForumsController extends Controller
{
  public function fetchForums(Request $Request)
  {
    $Response = array();
    $forums = ForumModel::orderBy('id', 'desc')->get();
    $forums = collect($forums)->map(function ($el) {
      $el->user = $el->user;
      $el->humanTime = $el->created_at->diffForHumans();
      return $el;
    });
    // check if the forums are not empty,,,
    if (count($forums) > 0) {
      $Response['status'] = 200;
      $Response['message'] = 'Successfully Fetched ' . count($forums) . ' records.';
      $Response['data'] = $forums;
      $Response['errors'] = [];

      return response()->json($Response, 200);
    }

    // Return a 204 Response header...
    return response()->json($Response, 204);
  }

  public function create(Request $Request)
  {
    $Response = array();
    $validator = Validator::make($Request->all(), [
      'title' => ['string', 'required'],
      'content' => ['string', 'required']
    ]);

    // check if validator fails...
    if ($validator->fails()) {
      $Response['data'] = [];
      $Response['status'] = 400;
      $Response['errors'] = $validator->errors();
      $Response['message'] = 'Some Errors Occured. Please, Try Again Later.';

      return response()->json($Response, 400);
    }

    // proceed with the next request...
    $user_id = $Request->user()->id;
    $title = stripcslashes(strip_tags($Request->input('title')));
    $content = $Request->input('content');
    $likes = 0;
    $comments = 0;

    // Let's cook some forums...
    try {
      $Forum = ForumModel::create([
        'title' => $title,
        'likes' => $likes,
        'content' => $content,
        'user_id' => $user_id,
        'comments' => $comments
      ]);

      // send back some data...
      $Response['data'] = $Forum;
      $Response['data']['humanTime'] = $Forum->created_at->diffForHumans();
      $Response['status'] = 201;
      $Response['errors'] = '';
      $Response['message'] = 'Your Forum Has Been Created Successfully.';

      return response()->json($Response, 201);
    } catch (Exception $e) {
      // send back the error message.........
      $Response['data'] = array();
      $Response['status'] = 500;
      $Response['errors'] = array(
        'title' => 'Sorry, An Unexpected Error Occurred And Your Request Could Not Be Completed.',
        'message' => $e->getMessage()
      );
      $Response['message'] = 'Sorry, Some Errors Occurred And Your Forum Couldn\'t Be Created.';

      return response()->json($Response, 500);
    }
  }

  public function read($forumId)
  {
    $Response = array();

    // fetch the record asap!!!
    try {
      $forum = ForumModel::find((Integer) ($forumId));

      // throw in some conditionals...
      if (empty($forum)) {
        return response()->json(array(), 204);
      }

      // send back some data...
      $Response['data'] = $forum;
      $Response['data']['user'] = $forum->user;
      $Response['status'] = 200;
      $Response['errors'] = '';
      $Response['timestamp'] = $forum->created_at->diffForHumans();
      $Response['message'] = 'Successfully Retrieved ' . $forum->title;

      return response()->json($Response, 200);
    } catch (Exception $e) {
      // send back the error message.........
      $Response['data'] = array();
      $Response['status'] = 500;
      $Response['errors'] = array(
        'title' => 'Sorry, An Unexpected Error Occurred And Your Request Could Not Be Completed.',
        'message' => $e->getMessage()
      );
      $Response['message'] = '';

      return response()->json($Response, 500);
    }
  }

  public function update(Request $Request, $forumId)
  {
    $Response = array();
    $validator = Validator::make($Request->all(), [
      'title' => ['string', 'required'],
      'content' => ['string', 'required']
    ]);

    // check for errors and send back a 400 HTTP Header...
    if ($validator->fails()) {
      $Response['data'] = [];
      $Response['status'] = 400;
      $Response['errors'] = $validator->errors();
      $Response['message'] = 'Some Errors Occured. Please, Try Again Later.';

      return response()->json($Response, 400);
    }

    try {
      // Check if the forum is valid...
      $forumId = stripcslashes(strip_tags((Integer) $forumId));
      $Forum = ForumModel::where('id', $forumId)->where('user_id', $Request->user()->id)->first();
      if (empty($Forum)) {
        return response()->json(array(), 204);
      }

      // Update The Forum.......
      $Forum->title = stripcslashes(strip_tags($Request->title));
      $Forum->content = $Request->content;
      $Forum->save();

      // send back some data...
      $Response['data'] = $Forum;
      $Response['status'] = 200;
      $Response['errors'] = '';
      $Response['message'] = 'Your Thread Has Been Updated Successfully';

      return response()->json($Response, 200);
    } catch (Exception $e) {
      // send back the error message.........
      $Response['data'] = array();
      $Response['status'] = 500;
      $Response['errors'] = array(
        'title' => 'Sorry, An Unexpected Error Occurred And Your Request Could Not Be Completed.',
        'message' => $e->getMessage()
      );
      $Response['message'] = '';

      return response()->json($Response, 500);
    }
  }

  public function delete(Request $request, $forumId)
  {
    $Response = array();
    try {
      $forumId = stripcslashes(strip_tags($forumId));
      $forum = ForumModel::find($forumId);
      $comments = $forum->commentModel;

      // delete all the comments first... #Delete the forum next...
      if (count($comments) > 0) {
        foreach ($comments as $comment) {
          $comment->delete();
        }
      }
      $forum->delete();

      // send back some data...
      $Response['data'] = array();
      $Response['status'] = 200;
      $Response['errors'] = '';
      $Response['message'] = 'The Selected Forum And It\'s Associated Comments Has Been Deleted Successfully.';

      return response()->json($Response, 200);
    } catch (Exception $e) {
      // send back the error message.........
      $Response['data'] = array();
      $Response['status'] = 500;
      $Response['errors'] = array(
        'title' => 'Sorry, An Unexpected Error Occurred And Your Request Could Not Be Completed.',
        'message' => $e->getMessage()
      );
      $Response['message'] = '';

      return response()->json($Response, 500);
    }
  }

  public function like($forumId)
  {
    $Response = array();
    try {
      $forum = ForumModel::find($forumId);

      // checkif the forum exits...
      if (empty($forum)) {
        return response()->json([], 204);
      }

      $forum->likes = (Integer) $forum->likes + 1;
      $forum->save();

      $Response['data'] = $forum;
      $Response['errors'] = [];
      $Response['status'] = 200;
      $Response['message'] = 'Your Action Has Been Saved Successfully.';

      return response()->json($Response, 200);
    } catch (Exception $e) {
      // send back the error message.........
      $Response['data'] = array();
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
