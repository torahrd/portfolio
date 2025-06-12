@props([
'title',
'description' => null,
'breadcrumbs' => [],
'actions' => null
])

<div class="bg-white dark:bg-gray-800 shadow">
  <div class="container-responsive py-6">
    <!-- Breadcrumbs -->
    @if(count($breadcrumbs) > 0)
    <nav class="mb-4" aria-label="Breadcrumb">
      <ol class="flex items-center space-x-2 text-sm text-gray-500">
        @foreach($breadcrumbs as $index => $breadcrumb)
        <li class="flex items-center">
          @if($index > 0)
          <i class="fas fa-chevron-right mx-2 text-gray-400"></i>
          @endif

          @if(isset($breadcrumb['url']) && $index < count($breadcrumbs) - 1)
            <a href="{{ $breadcrumb['url'] }}"
            class="hover:text-primary-600 transition-colors duration-200">
            {{ $breadcrumb['label'] }}
            </a>
            @else
            <span class="text-gray-900 dark:text-white font-medium">
              {{ $breadcrumb['label'] }}
            </span>
            @endif
        </li>
        @endforeach
      </ol>
    </nav>
    @endif

    <!-- Header content -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
      <div class="flex-1">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
          {{ $title }}
        </h1>

        @if($description)
        <p class="mt-2 text-gray-600 dark:text-gray-400">
          {{ $description }}
        </p>
        @endif
      </div>

      @if($actions)
      <div class="mt-4 sm:mt-0 sm:ml-4 flex space-x-2">
        {{ $actions }}
      </div>
      @endif
    </div>
  </div>
</div>