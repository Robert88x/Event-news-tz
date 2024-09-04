<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Discuss;


class DiscussController extends Controller
{
    public function index()
    {
        // return 'index';
        $alldiscussions = Discuss::get();
        dd($alldiscussions);

        return [
            'discussions' => $alldiscussions
        ];
    } 

    public function show(Request $request, User $user)
    {
        // dd($user);
        
        return 'show';
    } 

    public function edit(Request $request)
    {
        return 'edit';
    } 

    public function store(Request $request)
    {
        return 'store';
    } 

    public function update(Request $request)
    {
        return 'update';
    } 

    public function destroy(Request $request)
    {
        return 'destroy';
    } 
}
