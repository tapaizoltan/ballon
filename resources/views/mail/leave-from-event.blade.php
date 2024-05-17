<x-mail::message>
    <h3 class="greeting">
        Kedves {{ $user->name }}!
    </h3>
    <div class="content">
        <p>Sikeresen lejelentkeztél az alábbi kiírt időpontunkról.</p>
        <p>
            Repülés részletei:
            <br>
            <strong>Helyszín:</strong> {{ $event->region->name }}<br>
            <strong>Időpont:</strong> {{ $event->dateTime }}
        </p>
    </div>
</x-mail::message>
