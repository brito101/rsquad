<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\ContactRequest;
use App\Models\Contact;
use Eusonlito\LaravelMeta\Facade as Meta;

class ContactController extends Controller
{
    public function index()
    {
        $title = env('APP_NAME').' - Contato';
        $route = route('site.contact');
        $description = 'Entre em contato conosco!';
        /** Meta */
        Meta::title($title);
        Meta::set('description', $description);
        Meta::set('og:type', 'article');
        Meta::set('og:site_name', $title);
        Meta::set('og:locale', app()->getLocale());
        Meta::set('og:url', $route);
        Meta::set('twitter:url', $route);
        Meta::set('robots', 'index,follow');
        Meta::set('image', asset('img/share.png'));
        Meta::set('canonical', $route);

        return view('site.contact.index');
    }

    public function send(ContactRequest $request)
    {
        Contact::create($request->validated());
        $message = 'Mensagem enviada com sucesso! Em breve entraremos em contato.';

        $title = env('APP_NAME'.' - Contato');
        $route = route('site.contact');
        $description = 'Entre em contato conosco!';
        /** Meta */
        Meta::title($title);
        Meta::set('description', $description);
        Meta::set('og:type', 'article');
        Meta::set('og:site_name', $title);
        Meta::set('og:locale', app()->getLocale());
        Meta::set('og:url', $route);
        Meta::set('twitter:url', $route);
        Meta::set('robots', 'index,follow');
        Meta::set('image', asset('img/share.png'));
        Meta::set('canonical', $route);

        return view('site.contact.index', compact('message'));
    }
}
