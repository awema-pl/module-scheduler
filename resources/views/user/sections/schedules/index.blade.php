@extends('indigo-layout::main')

@section('meta_title', _p('scheduler::pages.user.schedule.meta_title', 'Schedules') . ' - ' . config('app.name'))
@section('meta_description', _p('scheduler::pages.user.schedule.meta_description', 'Schedules in schedule'))

@push('head')

@endpush

@section('title')
    {{ _p('scheduler::pages.user.schedule.headline', 'Schedules') }}
@endsection

@section('create_button')
    <button class="frame__header-add" @click="AWEMA.emit('modal::add_schedule:open')" title="{{ _p('scheduler::pages.user.schedule.add_schedule', 'Add schedule') }}"><i class="icon icon-plus"></i></button>
@endsection

@section('content')
    <div class="grid">
        <div class="cell-1-1 cell--dsm">
            <h4>{{ _p('scheduler::pages.user.schedule.schedules', 'Schedule') }}</h4>
            <div class="card">
                <div class="card-body">
                    <content-wrapper :url="$url.urlFromOnlyQuery('{{ route('scheduler.user.schedule.scope')}}', ['page', 'limit'], $route.query)"
                        :check-empty="function(test) { return !(test && (test.data && test.data.length || test.length)) }"
                        name="schedules_table">
                        <template slot-scope="table">
                            <table-builder :default="table.data">
                                <tb-column name="name" label="{{ _p('scheduler::pages.user.schedule.name', 'Name') }}"></tb-column>
                                <tb-column name="cron" label="{{ _p('scheduler::pages.user.schedule.cron', 'CRON') }}"></tb-column>
                                <tb-column name="activated" label="{{ _p('scheduler::pages.user.schedule.activated', 'Activated') }}">
                                    <template slot-scope="col">
                                        <span v-if="col.data.sandbox" class="cl-red">
                                            {{ _p('cheduler::pages.user.schedule.yes', 'Yes') }}
                                        </span>
                                        <span v-else>
                                            {{ _p('cheduler::pages.user.schedule.no', 'No') }}
                                        </span>
                                    </template>
                                </tb-column>
                                <tb-column name="created_at" label="{{ _p('scheduler::pages.user.schedule.created', 'Created at') }}"></tb-column>
                                <tb-column name="manage" label="{{ _p('scheduler::pages.user.schedule.options', 'Options')  }}">
                                    <template slot-scope="col">
                                        <context-menu right boundary="table">
                                            <button type="submit" slot="toggler" class="btn">
                                                {{_p('scheduler::pages.user.schedule.options', 'Options')}}
                                            </button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'editSchedule', data: col.data}); AWEMA.emit('modal::edit_schedule:open')">
                                                {{_p('scheduler::pages.user.schedule.edit', 'Edit')}}
                                            </cm-button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'deleteSchedule', data: col.data}); AWEMA.emit('modal::delete_schedule:open')">
                                                {{_p('scheduler::pages.user.schedule.delete', 'Delete')}}
                                            </cm-button>
                                        </context-menu>
                                    </template>
                                </tb-column>
                            </table-builder>

                            <paginate-builder v-if="table.data"
                                :meta="table.meta"
                            ></paginate-builder>
                        </template>
                        @include('indigo-layout::components.base.loading')
                        @include('indigo-layout::components.base.empty')
                        @include('indigo-layout::components.base.error')
                    </content-wrapper>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')

    <modal-window name="add_schedule" class="modal_formbuilder" title="{{ _p('scheduler::pages.user.schedule.add_schedule', 'Add schedule') }}">
        <form-builder name="add_schedule" url="{{ route('scheduler.user.schedule.store') }}" send-text="{{ _p('scheduler::pages.user.schedule.add', 'Add') }}"
                      @sended="AWEMA.emit('content::schedules_table:update')">
            <fb-input name="name" label="{{ _p('scheduler::pages.user.schedule.name', 'Name') }}"></fb-input>
            <fb-input name="cron" label="{{ _p('scheduler::pages.user.schedule.cron', 'CRON') }}"></fb-input>
            <h5 class="cl-caption mt-20 mb-0">{{ _p('scheduler::pages.user.schedule.schedule_type', 'Schedule type') }}</h5>
            <div class="mt-10">
                <fb-select name="schedulable_type" disabled-search :multiple="false" open-fetch options-value="id" options-name="name"
                           :url="'{{ route('scheduler.user.schedule.select_schedulable_type') }}'"
                           placeholder-text=" " label="{{ _p('scheduler::pages.user.schedule.select_schedule_type', 'Select the type of schedule') }}">
                </fb-select>
            </div>
            <div class="mt-10" v-if="AWEMA._store.state.forms['add_schedule'] && AWEMA._store.state.forms['add_schedule'].fields.schedulable_type">
                <fb-select name="schedulable_id" disabled-search :multiple="false" open-fetch options-value="id" options-name="name"
                           :url="'{{ route('scheduler.user.schedule.select_schedulable_id') }}?schedulable_type=' + AWEMA._store.state.forms['add_schedule'].fields.schedulable_type"
                           placeholder-text=" " label="{{ _p('storage::pages.user.source.select_schedule', 'Select a schedule') }}">
                </fb-select>
            </div>
           <div class="mt-10">
               <fb-switcher name="activated" label="{{ _p('scheduler::pages.user.schedule.activated', 'Activated') }}"></fb-switcher>
           </div>
        </form-builder>
    </modal-window>

    <modal-window name="edit_schedule" class="modal_formbuilder" title="{{ _p('scheduler::pages.user.schedule.edit_schedule', 'Edit schedule') }}">
        <form-builder name="edit_schedule" url="{{ route('scheduler.user.schedule.update') }}/{id}" method="patch"
                      @sended="AWEMA.emit('content::schedules_table:update')"
                      send-text="{{ _p('scheduler::pages.user.schedule.save', 'Save') }}" store-data="editSchedule">
            <div v-if="AWEMA._store.state.editSchedule">
                <fb-input name="name" label="{{ _p('scheduler::pages.user.schedule.name', 'Name') }}"></fb-input>
                <fb-input name="cron" label="{{ _p('scheduler::pages.user.schedule.cron', 'CRON') }}"></fb-input>
                <h5 class="cl-caption mt-20 mb-0">{{ _p('scheduler::pages.user.schedule.schedule_type', 'Schedule type') }}</h5>
                <div class="mt-10">
                    <fb-select name="schedulable_type" disabled-search :multiple="false" open-fetch auto-fetch options-value="id" options-name="name"
                               :url="'{{ route('scheduler.user.schedule.select_schedulable_type') }}'"
                               placeholder-text=" " label="{{ _p('scheduler::pages.user.schedule.select_schedule_type', 'Select the type of schedule.') }}"
                               :auto-fetch-value="AWEMA._store.state.editSource.schedulable_type">
                    </fb-select>
                </div>
                <div class="mt-10" v-if="AWEMA._store.state.forms['edit_schedule'] && AWEMA._store.state.forms['edit_schedule'].fields.schedulable_type">
                    <fb-select name="schedulable_id" disabled-search :multiple="false" open-fetch auto-fetch options-value="id" options-name="name"
                               :url="'{{ route('scheduler.user.schedule.select_schedulable_id') }}?schedulable_type=' + AWEMA._store.state.forms['edit_schedule'].fields.schedulable_type"
                               placeholder-text=" " label="{{ _p('storage::pages.user.source.select_schedule', 'Select a schedule') }}"
                               :auto-fetch-value="AWEMA._store.state.editSchedule.schedulable_id">
                    </fb-select>
                </div>
                <div class="mt-10">
                    <fb-switcher name="activated" label="{{ _p('scheduler::pages.user.schedule.activated', 'Activated') }}"></fb-switcher>
                </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="delete_schedule" class="modal_formbuilder" title="{{  _p('scheduler::pages.user.schedule.are_you_sure_delete', 'Are you sure delete?') }}">
        <form-builder :edited="true" url="{{route('scheduler.user.schedule.delete') }}/{id}" method="delete"
                      @sended="AWEMA.emit('content::schedules_table:update')"
                      send-text="{{ _p('scheduler::pages.user.schedule.confirm', 'Confirm') }}" store-data="deleteSchedule"
                      disabled-dialog>

        </form-builder>
    </modal-window>
@endsection
