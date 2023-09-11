@php

  $id=Auth::user()->id;
  $agentId=App\Models\User::find($id);
  $agentstatus=$agentId->status;
  
@endphp


<nav class="sidebar">
    <div class="sidebar-header">
      <a href="#" class="sidebar-brand">
        Agent<span> L10</span>
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




        @if ($agentstatus=='active')
        <h4>Agent Account is <span class="text-success">Active</span></h4>

        <li class="nav-item nav-category">RealEstate</li>
        



        

        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="collapse" href="#property" role="button" aria-expanded="false" aria-controls="emails">
            <i class="link-icon" data-feather="mail"></i>
            <span class="link-title">Property</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
          </a>
          <div class="collapse" id="property">
            <ul class="nav sub-menu">
              <li class="nav-item">
                <a href="{{route('agent.all.property')}}" class="nav-link">All Property</a>
              </li>
              
            </ul>
          </div>
        </li>
        
        


        <li class="nav-item">
          <a href="{{route('buy.package')}}" class="nav-link">
            <i class="link-icon" data-feather="calendar"></i>
            <span class="link-title">Buy Package</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{route('package.history')}}" class="nav-link">
            <i class="link-icon" data-feather="calendar"></i>
            <span class="link-title">Package History</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{route('agent.property.message')}}" class="nav-link">
            <i class="link-icon" data-feather="calendar"></i>
            <span class="link-title">Property Message</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{route('agent.schedule.request')}}" class="nav-link">
            <i class="link-icon" data-feather="calendar"></i>
            <span class="link-title">Schedule Request</span>
          </a>
        </li>
        <li class="nav-item nav-category">Component</li>
        <li class="nav-item">
          <a href="{{route('agent.live.chat')}}" class="nav-link">
            <i class="link-icon" data-feather="calendar"></i>
            <span class="link-title">Live Chat</span>
          </a>
        </li>

        <li class="nav-item nav-category">User All Function</li>
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="collapse" href="#uiComponents" role="button" aria-expanded="false" aria-controls="uiComponents">
            <i class="link-icon" data-feather="feather"></i>
            <span class="link-title">Manage Agent</span>
            <i class="link-arrow" data-feather="chevron-down"></i>
          </a>
          <div class="collapse" id="uiComponents">

            <ul class="nav sub-menu">
              <li class="nav-item">
                {{-- <a href="{{route('agent.all.property')}}" class="nav-link">All Agent</a> --}}
              </li>
              <li class="nav-item">
                <a href="pages/ui-components/alerts.html" class="nav-link">Add Agent</a>
              </li>
              </ul>

          </div>
        </li>
       

        @else
        
       
        
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

  