@extends('layouts.public', ['title' => 'Demandes des habitants'])

@section('content')
<x-public.breadcrumb :items="[['Demandes des habitants']]" />
<x-public.page-hero title="Demandes des habitants" lead="Un problème a peut-être déjà été signalé par un voisin. Soutenez sa demande plutôt que d'en créer une autre : plus elle est soutenue, plus elle compte." />

<livewire:reports-list />
@endsection