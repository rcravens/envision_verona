<div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Survey - Details
                </div>
                <div class="panel-body">

                    <form wire:submit.prevent="save_survey">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">Title</label>
                                    <input wire:model.lazy="template.title" class="form-control" required type="text"/>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Department</label>
                                    <select wire:model.lazy="template.department" class="form-control" required>
                                        @foreach(\AssetIQ\Models\DepartmentOptions::all() as $department)
                                            <option value="{{$department}}">{{$department}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6">

                                <div class="form-group">
                                    <label class="control-label">Description</label>
                                    <textarea wire:model.lazy="template.description" class="form-control" type="text" rows="5"></textarea>
                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <div class="col-md-12" style="text-align:right;">
                                <button type="submit" class="btn btn-primary" @if(!$template->isDirty()) disabled @endif>
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            @forelse($sections as $section)
                @livewire('surveys.template-section-editor', ['section' => $section], key($section->id))
            @empty
                <em>No sections found.</em>
            @endforelse
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form wire:submit.prevent="add_section">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <input wire:model.defer="new_section_title" class="form-control" placeholder="Title...." required type="text"/>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <input wire:model.defer="new_section_description" class="form-control" placeholder="Description..." type="text"/>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label><input wire:model.defer="new_section_is_cloneable" type="checkbox"/> Is Repeatable?</label>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Add Section</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="delete_section_confirmation_modal" tabindex="-1" role="dialog" aria-labelledby="delete_section_confirmation_modal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Confirm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure want to delete the section and all questions?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
                    <button type="button" wire:click.prevent="delete_section()" class="btn btn-danger close-modal" data-dismiss="modal">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="delete_question_confirmation_modal" tabindex="-1" role="dialog"
         aria-labelledby="delete_question_confirmation_modal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Confirm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure want to delete the question?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
                    <button id="delete_question" type="button" class="btn btn-danger close-modal"
                            data-dismiss="modal">Yes, Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        (function($){
            var section_id = null;

            $(document).ready(function(){
                window.livewire.on('show_delete_section_confirmation_modal', function(){
                    $('#delete_section_confirmation_modal').modal('toggle');
                })

                var that = this;
                window.livewire.on('show_delete_question_confirmation_modal', function (section_id) {
                    that.section_id = section_id;
                    $('#delete_question_confirmation_modal').modal('toggle');
                })

                $('#delete_question').click(function () {
                    window.livewire.emit('delete_question_' + that.section_id);
                })
            });

        })(jQuery);
    </script>
@endpush
