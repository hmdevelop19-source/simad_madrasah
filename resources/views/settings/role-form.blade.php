@extends('layouts.app')
@section('title', $isEdit ? 'Edit Role' : 'Tambah Role')
@section('page-title', $isEdit ? 'Otoritas Pengguna' : 'Peran Baru')

@section('content')
<style>
    .role-container { background: white; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); overflow: hidden; }
    .perm-table { width: 100%; border-collapse: collapse; background: white; }
    .perm-table th { background: #fff; padding: 1.25rem 1rem; color: #8a99af; font-weight: 800; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1.2px; border-bottom: 1.5px solid #edf2f7; text-align: center; }
    .perm-table th:first-child { text-align: left; padding-left: 2.5rem; width: 320px; }
    .perm-table td { padding: 1.1rem 1rem; border-bottom: 1px solid #f1f5f9; text-align: center; vertical-align: middle; color: #4b5563; font-size: 0.875rem; }
    .perm-table td:first-child { text-align: left; padding-left: 2.5rem; font-weight: 700; color: #1e293b; }
    
    .group-row { background: #fafbfc !important; }
    .group-row td:first-child { padding-left: 2rem !important; font-weight: 900 !important; color: var(--primary) !important; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; }
    .sub-module { padding-left: 4.5rem !important; color: #64748b !important; font-weight: 600 !important; border-left: 3px solid #f1f5f9; }

    .badge-grp { background: #f1f5f9; color: #475569; padding: 2px 10px; border-radius: 50px; font-size: 0.65rem; font-weight: 800; margin-left: 8px; vertical-align: middle; border: 1px solid #e2e8f0; }
    
    /* Rounded Checkboxes */
    .round-cb { width: 22px; height: 22px; cursor: pointer; border-radius: 6px; border: 2px solid #cbd5e1; appearance: none; -webkit-appearance: none; outline: none; transition: all 0.2s; position: relative; margin: 0 auto; display: block; background: #fff; }
    .round-cb:checked { background: #3b82f6; border-color: #3b82f6; }
    .round-cb:checked::after { content: ''; position: absolute; left: 6.5px; top: 2px; width: 6px; height: 11px; border: solid white; border-width: 0 2.5px 2.5px 0; transform: rotate(45deg); }
    .round-cb:hover { border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }

    .dash-placeholder { color: #e2e8f0; font-weight: 400; font-size: 1.2rem; }
    
    .lainnya-input { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 6px 12px; font-size: 0.75rem; width: 100%; max-width: 140px; color: #94a3b8; outline: none; }
    .lainnya-input:focus { border-color: #3b82f6; background: #fff; }

    .role-title-input { font-size: 1.5rem; font-weight: 900; color: var(--primary); border: none; background: transparent; padding: 0; margin: 0; outline: none; width: 100%; transition: all 0.3s; letter-spacing: -0.5px; border-bottom: 2px solid transparent; }
    .role-title-input:focus { border-bottom-color: var(--accent); }
    .role-title-input::placeholder { color: #cbd5e1; }
    .role-title-input[readonly] { cursor: default; }

    .select-all-btn { background: #eff6ff; border: 1px solid #dbeafe; color: #3b82f6; font-size: 0.6rem; font-weight: 900; text-transform: uppercase; cursor: pointer; padding: 2px 8px; border-radius: 6px; margin-top: 5px; transition: all 0.2s; display: inline-block; }
    .select-all-btn:hover { background: #3b82f6; color: white; border-color: #3b82f6; }
</style>

<div class="row justify-content-center">
    <div class="col-12">
        <form action="{{ $isEdit ? route('roles.update', $role->id) : route('roles.store') }}" method="POST">
            @csrf
            @if($isEdit) @method('PUT') @endif

            {{-- HEADER NAV --}}
            <div class="d-flex justify-content-between align-items-center mb-5 px-2">
                <div style="flex: 1; max-width: 450px;">
                    <span style="font-size: 0.65rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px; display: block; margin-bottom: 4px;">PENGATURAN AKSES</span>
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #f1f5f9; border-radius: 10px; color: var(--primary); border: 1px solid #e2e8f0;">
                            <i class="fa-solid fa-shield-halved" style="font-size: 1.1rem; opacity: 0.7;"></i>
                        </div>
                        <div style="flex: 1;">
                            <input type="text" name="name" value="{{ $role->name ?? '' }}" 
                                class="role-title-input" 
                                placeholder="Nama Peran / Jabatan..." 
                                {{ $role->name === 'super_admin' ? 'readonly' : '' }}
                                required>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-3">
                    <a href="{{ route('roles.index') }}" class="btn btn-light" style="border-radius: 12px; font-weight: 800; color: #64748b; padding: 12px 25px; border: 1.5px solid #e2e8f0;">
                        <i class="fa-solid fa-arrow-left me-2"></i> KEMBALI
                    </a>
                    <button type="submit" class="btn btn-primary" style="background: var(--primary); border: none; border-radius: 12px; font-weight: 900; padding: 12px 35px; box-shadow: 0 10px 20px rgba(0,0,82,0.15);">
                        <i class="fa-solid fa-floppy-disk me-2"></i> SIMPAN PERUBAHAN
                    </button>
                </div>
            </div>

            <div class="role-container">
                <table class="perm-table">
                    <thead>
                        <tr>
                            <th>IDENTITAS MODUL</th>
                            <th>VIEW <br> <button type="button" class="select-all-btn" onclick="toggleCol('view')">ALL</button></th>
                            <th>CREATE <br> <button type="button" class="select-all-btn" onclick="toggleCol('create')">ALL</button></th>
                            <th>EDIT <br> <button type="button" class="select-all-btn" onclick="toggleCol('edit')">ALL</button></th>
                            <th>DELETE <br> <button type="button" class="select-all-btn" onclick="toggleCol('delete')">ALL</button></th>
                            <th>APPROVE <br> <button type="button" class="select-all-btn" onclick="toggleCol('approve')">ALL</button></th>
                            <th>LAINNYA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($matrix as $groupName => $groupData)
                            @if($groupData['group'])
                            <tr class="group-row">
                                <td>
                                    <i class="fa-solid fa-folder-open me-2" style="opacity: 0.4;"></i>
                                    {{ $groupName }} <span class="badge-grp">Group</span>
                                </td>
                                <td><span class="dash-placeholder">-</span></td>
                                <td><span class="dash-placeholder">-</span></td>
                                <td><span class="dash-placeholder">-</span></td>
                                <td><span class="dash-placeholder">-</span></td>
                                <td><span class="dash-placeholder">-</span></td>
                                <td><span class="dash-placeholder">-</span></td>
                            </tr>
                            @endif

                            @foreach($groupData['modules'] as $moduleLabel => $moduleKey)
                            <tr>
                                <td class="{{ $groupData['group'] ? 'sub-module' : '' }}">
                                    @if($groupData['group']) <i class="fa-solid fa-chevron-right me-2" style="font-size: 0.6rem; opacity: 0.3;"></i> @endif
                                    {{ $moduleLabel }}
                                </td>
                                @foreach(['view', 'create', 'edit', 'delete', 'approve'] as $action)
                                    @php 
                                        $permName = $action . '-' . $moduleKey;
                                        $exists = $permissions->contains('name', $permName);
                                        $checked = $isEdit && in_array($permName, $rolePermissions);
                                    @endphp
                                    <td>
                                        @if($exists)
                                            <input type="checkbox" name="permissions[]" value="{{ $permName }}" class="round-cb cb-{{ $action }}" {{ $checked ? 'checked' : '' }}>
                                        @else
                                            <span class="dash-placeholder">-</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td>
                                    <input type="text" class="lainnya-input" placeholder="Tambah..." disabled>
                                </td>
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleCol(action) {
        const cbs = document.querySelectorAll('.cb-' + action);
        const allChecked = Array.from(cbs).every(cb => cb.checked);
        cbs.forEach(cb => cb.checked = !allChecked);
    }
</script>
@endsection
