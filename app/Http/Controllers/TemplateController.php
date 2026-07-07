<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Contracts\TemplateRepositoryInterface;

class TemplateController extends Controller
{
    public function __construct(protected TemplateRepositoryInterface $templates)
    {
    }

    // ── Index ─────────────────────────────────────────────────────
    public function index()
    {
        $templates = collect($this->templates->all());
        return view('templates.index', compact('templates'));
    }

    // ── Use Template ──────────────────────────────────────────────
    public function use(Request $request, $templateId)
    {
        $template = $this->templates->find($templateId);

        if (!$template) {
            return redirect()->route('templates.index')->with('error', 'Template not found.');
        }

        $waiver = $this->templates->createWaiverFromTemplate(auth()->id(), $template);

        return redirect()->route('waivers.edit', $waiver)
                         ->with('success', '✅ Template "' . $template['title'] . '" added! Customize it below.');
    }
}