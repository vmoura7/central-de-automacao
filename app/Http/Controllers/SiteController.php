<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Site;

class SiteController extends Controller
{
        public function index()
        {
            $search = request('search');
            $sites = Site::when($search, function ($query) use ($search) {
                return $query->where('url', 'like', '%' . $search . '%');
            })->paginate(10);
        
            return view('sites.index', compact('sites'));
        }
    
        public function create()
        {
            return view('sites.create');
        }
    
        public function edit(Site $site)
        {
            return view('sites.edit', compact('site'));
        }
    
    
        public function store(Request $request)
        {
            $request->validate([
                'url' => 'required|url|unique:sites',
            ]);
    
            Site::create(['url' => $request->url]);
    
            return redirect()->route('sites.index')->with('success', 'Site adicionado com sucesso!');
        }
    
        public function update(Request $request, Site $site)
        {
            $request->validate([
                'url' => 'required|url|unique:sites,url,' . $site->id,
            ]);
    
            $site->update(['url' => $request->url]);
    
            return redirect()->route('sites.index')->with('success', 'Site atualizado com sucesso!');
        }
    
        public function destroy(Site $site)
        {
            $site->delete();
    
            return redirect()->route('sites.index')->with('success', 'Site excluído com sucesso!');
        }
}
