@extends('layouts.app')

@section('content')
<div class="md:mx-4 relative overflow-hidden">
    <main class="h-full flex flex-col overflow-auto">
        <div class="relative p-2 flex overflow-x-auto h-full containment">

            @foreach ($statuses as $status)
                <div class="mr-6 w-4/5 max-w-xs flex-1 flex-shrink-0">
                    <div class="rounded-md shadow-md overflow-hidden">
                        <div class="p-3 flex justify-between items-baseline bg-blue-800 ">
                            <h4 class="font-medium text-white">
                                {{ $status->title }}
                            </h4>
                            <button class="open-modal py-1 px-2 text-sm text-orange-500 hover:underline" data-status="{{$status->id}}" data-status-title="{{$status->title}}">
                                Add Task
                            </button>
                        </div>
                        <div class="p-2 flex-1 flex flex-col h-full overflow-x-hidden overflow-y-auto bg-blue-100 sortable connected-sort items" id="sort-{{$status->id}}" data-status-id="{{$status->id}}">

                            <!-- Tasks -->
                            @isset($tasks[$status->id])
                                @if (count($tasks[$status->id])>0)

                                    @foreach ($tasks[$status->id] as $task)
                                        <div class="mb-3 p-3 h-24 flex flex-col bg-white rounded-md shadow transform hover:shadow-md cursor-pointer" data-task-id="{{$task['id']}}">
                                            <span class="block mb-2 text-xl text-gray-900">
                                                {{ $task['title'] }}
                                            </span>
                                            <a href="{{route('tasks.delete', ['taskId'=>$task['id']])}}">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </a>
                                        </div>
                                    @endforeach

                                @else
                                    <!-- No Tasks -->
                                    <div class="flex-1 p-4 flex flex-col items-center justify-center">
                                        <span class="text-gray-600">No tasks yet</span>
                                        <button class="mt-1 text-sm text-orange-600 hover:underline">
                                        Add one
                                        </button>
                                    </div>
                                    <!-- ./No Tasks -->
                                @endif
                            @endisset
                            <!-- ./Tasks -->
                        </div>
                    </div>
                </div>
            @endforeach
          </div>
    </main>
</div>

<div class="relative z-10 invisible" aria-labelledby="modal-title" role="dialog" aria-modal="true" id="add-task-modal">
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Add a Task to <span class="suffix"></span></h3>
                <!-- Add task Form -->


                <form class="relative mb-3 flex flex-col justify-between bg-white rounded-md shadow overflow-hidden" action="{{route('tasks.create')}}" method="POST" id="add-task-form">
                    <div class="p-3 flex-1">
                        <input class="block w-full px-2 py-1 text-lg border-b border-blue-800 rounded" type="text" placeholder="Enter a title" name="title" />
                        <input type="hidden" id="status" name="status_id" value="" />
                        {!! csrf_field() !!}

                        {{-- TODO: error handling/validation --}}
                        </div>

                        <div class="p-3 flex justify-between items-end text-sm bg-gray-100">
                        <button type="reset" class="close-modal py-1 leading-5 text-gray-600 hover:text-gray-700">
                            Cancel
                        </button>
                        <button type="submit" class="px-3 py-1 leading-5 text-white bg-orange-600 hover:bg-orange-500 rounded">
                            Add
                        </button>
                    </div>
                </form>
                <!-- end add task form -->
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('.open-modal').on('click', function(e){
            jQuery('#add-task-modal').removeClass('invisible');
            var status = jQuery(this).attr('data-status');
            var statusTitle = jQuery(this).attr('data-status-title');
            jQuery('#add-task-modal input#status').val(status);
            jQuery('#add-task-modal #modal-title span.suffix').text(statusTitle);
        });
        jQuery('.close-modal').on('click', function(e){
            jQuery('#add-task-modal').addClass('invisible');
        });
    });

    $(function() {
		$('.sortable').sortable({
            connectWith : ".sortable",
            dropOnEmpty: true,
            receive : function(e, ui) {
                var status_id = $(ui.item).parent(".sortable").data( "status-id");
                var task_id = $(ui.item).data("task-id");

                // var url = "{{route('tasks.update',['taskId'=>':taskId', 'statusId'=>':statusId'])}}";
                var url = "{{route('tasks.update',['taskId'=>':taskId', 'statusId'=>':statusId'])}}";

                console.log(url);

                url = url.replace(':statusId', status_id);
                url = url.replace(':taskId', task_id);


                $.ajax({
                    url : url,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success : function(response) {
                    }
                });
            }

        });
	});
</script>

@endsection
