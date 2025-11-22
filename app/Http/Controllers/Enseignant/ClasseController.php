<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    public function index()
    {
        $enseignant = auth()->user()->enseignant;
        
        $affectations = $enseignant->affectations()
            ->with(['matiere', 'niveau.filiere', 'niveau.inscriptions.etudiant'])
            ->where('annee_academique', '2024-2025')
            ->get();
        
        return view('enseignant.classes.index', compact('affectations'));
    }
}