  <x-filament::page>
      <div x-data x-init="$wire.generatePlagiarismReport()">
          <div class="space-y-6">
              @include('partials.analyzing')
          </div>
      </div>
  </x-filament::page>
