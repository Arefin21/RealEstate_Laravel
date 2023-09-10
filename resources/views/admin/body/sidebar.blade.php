<nav class="sidebar">
    <div class="sidebar-header">
      <a href="#" class="sidebar-brand">
        Admin<span> L10</span>
      </a>
      <div class="sidebar-toggler not-active">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
    <div class="sidebar-body">
      <ul class="nav">
        <li class="nav-item nav-category">Main</li>
        <li class="nav-item">
          <a href="{{route('admin.dashboard')}}" class="nav-link">
            <i class="link-icon" data-feather="box"></i>
            <span class="link-title">Dashboard</span>
          </a>
        </li>
        <li class="nav-item nav-category">RealEstate</li>

        @if (Auth::user()->can('type.menu'))
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="collapse" href="#emails" role="button" aria-expanded="false" aria-controls="emails">
            <i class="link-icon" data-feather="mail"></i>
            <span class="link-title">Property Type</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
          </a>
          <div class="collapse" id="emails">
            <ul class="nav sub-menu">

              @if (Auth::user()->can('all.type'))
              <li class="nav-item">
                <a href="{{route('all.type')}}" class="nav-link">All Type</a>
              </li>
              @endif

              @if (Auth::user()->can('add.type'))
              <li class="nav-item">
                <a href="{{route('add.type')}}" class="nav-link">Add Type</a>
              </li>
              @endif
            </ul>
          </div>
        </li>
        @endif

        @if (Auth::user()->can('amenities.menu'))
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="collapse" href="#amenitie" role="button" aria-expanded="false" aria-controls="emails">
            <i class="link-icon" data-feather="mail"></i>
            <span class="link-title">Amenitie</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
          </a>
          <div class="collapse" id="amenitie">
            <ul class="nav sub-menu">
              @if (Auth::user()->can('amenities.all'))
              <li class="nav-item">
                <a href="{{route('all.amenitie')}}" class="nav-link">All Amenitie</a>
                @endif
              </li>
              @if (Auth::user()->can('amenities.add'))
              <li class="nav-item">
                <a href="pages/email/compose.html" class="nav-link">Add Amenitie</a>
                @endif
              </li>
            </ul>
          </div>
        </li>
        @endif

        @if (Auth::user()->can('property.menu'))
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="collapse" href="#property" role="button" aria-expanded="false" aria-controls="emails">
            <i class="link-icon" data-feather="mail"></i>
            <span class="link-title">Property</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
          </a>
          <div class="collapse" id="property">
            <ul class="nav sub-menu">
              @if (Auth::user()->can('property.all'))
              <li class="nav-item">
                <a href="{{route('all.property')}}" class="nav-link">All Property</a>
              </li>
              @endif
              @if (Auth::user()->can('property.add'))
              <li class="nav-item">
                <a href="{{route('add.property')}}" class="nav-link">Add Property</a>
              </li>
              @endif
            </ul>
          </div>
        </li>
        @endif

        @if (Auth::user()->can('state.menu'))
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="collapse" href="#state" role="button" aria-expanded="false" aria-controls="emails">
            <i class="link-icon" data-feather="mail"></i>
            <span class="link-title">Property State</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
          </a>
          <div class="collapse" id="state">
            <ul class="nav sub-menu">
              @if (Auth::user()->can('state.all'))
              <li class="nav-item">
                <a href="{{route('all.state')}}" class="nav-link">All State</a>
              </li>
              @endif
              @if (Auth::user()->can('state.add'))
              <li class="nav-item">
                <a href="{{route('add.state')}}" class="nav-link">Add State</a>
              </li>
              @endif
            </ul>
          </div>
        </li>
        @endif

        {{-- @if (Auth::user()->can('history.menu')) --}}
        <li class="nav-item">
          <a href="{{route('admin.package.history')}}" class="nav-link">
            <i class="link-icon" data-feather="calendar"></i>
            <span class="link-title">Package History</span>
          </a>
        </li>
        {{-- @endif --}}

        <li class="nav-item">
          <a href="{{route('admin.property.message')}}" class="nav-link">
            <i class="link-icon" data-feather="calendar"></i>
            <span class="link-title">Property Message</span>
          </a>
        </li>

        @if (Auth::user()->can('testimonials.menu'))
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="collapse" href="#testimonials" role="button" aria-expanded="false" aria-controls="emails">
            <i class="link-icon" data-feather="mail"></i>
            <span class="link-title">Testimonials Manage</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
          </a>
          <div class="collapse" id="testimonials">
            <ul class="nav sub-menu">
              @if (Auth::user()->can('testimonials.all'))
              <li class="nav-item">
                <a href="{{route('all.testimonials')}}" class="nav-link">All Testimonials</a>
              </li>
              @endif
              @if (Auth::user()->can('testimonials.add'))
              <li class="nav-item">
                <a href="{{route('add.testimonials')}}" class="nav-link">Add Testimonials</a>
              </li>
              @endif
            </ul>
          </div>
        </li>
        @endif

        <li class="nav-item nav-category">User All Function</li>

        @if (Auth::user()->can('agent.menu'))
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="collapse" href="#uiComponents" role="button" aria-expanded="false" aria-controls="uiComponents">
            <i class="link-icon" data-feather="feather"></i>
            <span class="link-title">Manage Agent</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
          </a>
          <div class="collapse" id="uiComponents">

            <ul class="nav sub-menu">
              @if (Auth::user()->can('agent.all'))
              <li class="nav-item">
                <a href="{{route('all.agent')}}" class="nav-link">All Agent</a>
              </li>
              @endif
              @if (Auth::user()->can('agent.add'))
              <li class="nav-item">
                <a href="{{route('add.agent')}}" class="nav-link">Add Agent</a>
              </li>
              @endif
              </ul>
          </div>
        </li>
        @endif

        
        @if (Auth::user()->can('category.menu'))
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="collapse" href="#blogcategory" role="button" aria-expanded="false" aria-controls="uiComponents">
            <i class="link-icon" data-feather="feather"></i>
            <span class="link-title">Blog Category</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
          </a>
          <div class="collapse" id="blogcategory">

            <ul class="nav sub-menu">
              @if (Auth::user()->can('category.add'))
              <li class="nav-item">
                <a href="{{route('all.blog.category')}}" class="nav-link">All Blog Category</a>
              </li>
              @endif
              </ul>
          </div>
        </li>
        @endif

        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="collapse" href="#post" role="button" aria-expanded="false" aria-controls="uiComponents">
            <i class="link-icon" data-feather="feather"></i>
            <span class="link-title">Blog post</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
          </a>
          <div class="collapse" id="post">

            <ul class="nav sub-menu">
              <li class="nav-item">
                <a href="{{route('all.post')}}" class="nav-link">All Post</a>
              </li>
              <li class="nav-item">
                <a href="{{route('all.blog.category')}}" class="nav-link">Add Post</a>
              </li>
              </ul>
          </div>
        </li>

        <li class="nav-item">
          <a href="{{route('admin.blog.comment')}}" class="nav-link">
            <i class="link-icon" data-feather="calendar"></i>
            <span class="link-title">Blog Comment</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{route('smtp.setting')}}" class="nav-link">
            <i class="link-icon" data-feather="calendar"></i>
            <span class="link-title">SMTP Setting</span>
          </a>
        </li>

        
        <li class="nav-item">
          @if (Auth::user()->can('site.menu'))
          <a href="{{route('site.setting')}}" class="nav-link">
            <i class="link-icon" data-feather="calendar"></i>
            <span class="link-title">Site Setting</span>
          </a>
          @endif
        </li>
       



        <li class="nav-item nav-category">Role & Permission </li>

        @if (Auth::user()->can('role.menu'))
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="collapse" href="#permission" role="button" aria-expanded="false" aria-controls="permission">
            <i class="link-icon" data-feather="anchor"></i>
            <span class="link-title">Role & Permission</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
          </a>
          <div class="collapse" id="permission">
            <ul class="nav sub-menu">
              @if (Auth::user()->can('permission.all'))
              <li class="nav-item">
                <a href="{{route('all.permission')}}" class="nav-link">All Permission</a>
              </li>
              @endif
              @if (Auth::user()->can('role.all'))
              <li class="nav-item">
                <a href="{{route('all.roles')}}" class="nav-link">All Roles</a>
              </li>
              @endif
              @if (Auth::user()->can('rolePermission.add'))
              <li class="nav-item">
                <a href="{{route('add.roles.permission')}}" class="nav-link">Role in Permission</a>
              </li>
               @endif

              @if (Auth::user()->can('rolePermission.all')) 
              <li class="nav-item">
                <a href="{{route('all.roles.permission')}}" class="nav-link">All Role in Permission</a>
              </li>
               @endif
            </ul>
          </div>
        </li>
@endif
              @if (Auth::user()->can('manage.admin'))
              <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#admin" role="button" aria-expanded="false" aria-controls="admin">
                  <i class="link-icon" data-feather="anchor"></i>
                  <span class="link-title">Manage Admin</span>
                  <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="admin">
                  <ul class="nav sub-menu">
                    <li class="nav-item">
                      <a href="{{route('all.admin')}}" class="nav-link">All Admin</a>
                    </li>
                    <li class="nav-item">
                      <a href="{{route('add.admin')}}" class="nav-link">Add Admin</a>
                    </li>
                  </ul>
                </div>
              </li>
              @endif
       
        <li class="nav-item nav-category">Docs</li>
        <li class="nav-item">
          <a href="https://www.nobleui.com/html/documentation/docs.html" target="_blank" class="nav-link">
            <i class="link-icon" data-feather="hash"></i>
            <span class="link-title">Documentation</span>
          </a>
        </li>
      </ul>
    </div>
  </nav>

  