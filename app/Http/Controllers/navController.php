<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Nav;

class navController extends Controller
{
    function loadMenu(){
        $res = [];
        $l1 = Nav::where('parent_id',5)->get();
        foreach ($l1 as $comp1){
            $tmp = [];
            $l2 = Nav::where('parent_id',$comp1['id'])->get();
            $content2=[];
            foreach ($l2 as $comp2){
                $tmp2 = [];
                $l3 = Nav::where('parent_id',$comp2['id'])->get();
                $content=[];
                foreach ($l3 as $comp3){
                    $tmp3=[];
                    $tmp3['name'] = $comp3['title'];
                    $tmp3['tag_id'] = $comp3['tag_id'];
                    array_push($content,$tmp3);
                }
                $tmp2['name'] = $comp2['title'];
                $tmp2['tag_id'] = $comp2['tag_id'];
                $tmp2['content'] = $content;
                array_push($content2,$tmp2);
            }
            $tmp['name'] = $comp1['title'];
            $tmp['tag_id'] = $comp1['tag_id'];
            $tmp['content'] = $content2;
            array_push($res,$tmp);
        }
        return response()->json($res,200);
    }
}
