<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Set;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hashids\Hashids;

class SetController extends Controller
{
    public function index()
    {
        return view("sets.index", [
            'sets' => Set::all()
        ]);
    }
    //Show single tournament
    public function show($id)
    {
        return view("sets.show", [
            'set' => Set::find($id)
        ]);
    }
    public function create()
    {
        return view("sets.create");
    }

    public function insert()
    {
        return view('sets.insert');
    }
    
    public function store(Request $request)
    {
        $set = new Set;

        $set->name = $request->name;
        $set->user_id = Auth::user()->id;
        $hashids = new Hashids();
        $reference_code = $hashids->encode($set->id, $set->id - 1);
        $set->reference_code = $reference_code;
        $set->save();
        $set->refresh();
        $reference_code = $hashids->encode($set->id, $set->id - 1);
        $set->reference_code = $reference_code;
        $set->save();

        return redirect('sets/create')->with('status', 'Set: '.$set->name.' has been created! Set code:'. $set->reference_code);
    }

    public function edit($id)
    {
        $set = Set::find($id);

        return view('sets.edit', [
            'set' => $set,
        ]);
    }

    public function update(Request $request, $id)
    {
        $set = Set::find($id);

        $set->name = $request->name;
        $set->save();

        $card = new Card;

        $card->set_id = $set->id;

        if($request->card_content != '')
        {
            $isQuestion = ($request->card_type === 'Question Card');
            if($isQuestion && str_contains($request->card_content, '__'))
            {
                $card->card_description = $request->card_content;
                $card->is_question = $isQuestion;
                $card->save();
            }
            else if($isQuestion)
            {
                return redirect('sets/edit/'.$id)->with('status', 'Could not update sets or/and cards!');
            }
            else
            {
                $card->card_description = $request->card_content;
                $card->is_question = $isQuestion;
                $card->save();
            }
            
        }

        foreach ($set->getAllCards() as $card) {
            $cardTypeKey = 'card_type' . $card->id;
            $cardContentKey = 'card_content' . $card->id;
    
            // Check if the card data is present in the request
            if ($request->has($cardTypeKey) && $request->has($cardContentKey)) {
                $isQuestion = ($request->$cardTypeKey === 'Question Card');
    
                // Check if the card with the given ID exists in the database
                $existingCard = Card::find($card->id);
    
                if ($existingCard) {
                    if ($isQuestion && str_contains($request->$cardContentKey, '__')) {
                        $existingCard->card_description = $request->$cardContentKey;
                        $existingCard->is_question = $isQuestion;
                        $existingCard->save();
                    } elseif ($isQuestion) {
                        return redirect('sets/edit/' . $id)->with('status', 'Could not update sets or/and cards!');
                    } else {
                        $existingCard->card_description = $request->$cardContentKey;
                        $existingCard->is_question = $isQuestion;
                        $existingCard->save();
                    }
                }
            }
        }

        if ($request->has('removed_cards')) {
            $removedCardIds = $request->input('removed_cards');
    
            foreach ($removedCardIds as $removedCardId) {
                $card = Card::find($removedCardId);
    
                if ($card) {
                    $card->delete();
                }
            }
        }

        return redirect('sets/edit/'.$id)->with('status', 'Set and cards have been updated!');
    }

    public function removeCard($setId, $cardId)
    {
        // Implement the logic to remove the card from the database
        $card = Card::find($cardId);

        if ($card) {
            $card->delete();
            return response()->json(['success' => true], 200);
        } else {
            return response()->json(['error' => 'Card not found'], 404);
        }
    }


    public function browseOwnSets($id)
    {
        return view("sets.show_own_sets",[
            'sets' => Set::getAllUserSets($id),
        ]);
    }
}