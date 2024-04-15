<div class="section-sidebar-alpha-container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="section-sidebar-alpha-item">
				<div class="section-sidebar-alpha-item-heading">
					<i class="fa-icon fa-solid fa-bullseye"></i> <h3>{{__('message.keywords')}}</h3>
				</div>
				<div class="section-sidebar-alpha-item-content">
                    <input type="hidden" id="candidates-view-type" value="{{$view ? $view : 'list'}}">
                    <input type="hidden" id="jobs-page" value="{{$page}}">					
					<input type="text" class="candidates-search-value" value="{{$search}}" name="" placeholder="" />
				</div>
				<div class="section-sidebar-alpha-item-content">
				</div>
				<div class="section-sidebar-alpha-item-button">
					<button class="btn candidates-search-btn">{{__('message.search')}}</button>
				</div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="section-sidebar-alpha-item">
				<div class="section-sidebar-alpha-item-heading">
					<i class="fa-icon fa-solid fa-user-tie"></i> <h3>{{__('message.experiences')}}</h3>
				</div>
				<div class="section-sidebar-alpha-item-content">
					<label class="form-label">{{__('message.keywords')}}</label>
					<input type="text" name="" class="candidates-experiences-value" value="{{$candidates_experiences_value}}" />
				</div>
				<div class="section-sidebar-alpha-item-content">
					<label class="form-label">{{__('message.minimum_items')}} <span class="profile-item-value">(0)</span></label>
					<input type="range" min="0" max="20" value="{{$candidates_experiences_range}}"
						class="candidates-experiences-range section-sidebar-alpha-item-content-handle profile-item-handle form-range" />
				</div>
				<div class="section-sidebar-alpha-item-button">
					<button class="btn candidates-experiences-btn">{{__('message.filter')}}</button>
				</div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="section-sidebar-alpha-item">
				<div class="section-sidebar-alpha-item-heading">
					<i class="fa-icon fa-solid fa-graduation-cap"></i> <h3>{{__('message.qualifications')}}</h3>
				</div>
				<div class="section-sidebar-alpha-item-content">
					<label class="form-label">{{__('message.keywords')}}</label>
					<input type="text" name="" class="candidates-qualifications-value" value="{{$candidates_qualifications_value}}" />
				</div>
				<div class="section-sidebar-alpha-item-content">
					<label class="form-label">{{__('message.minimum_items')}} <span class="profile-item-value">(0)</span></label>
					<input type="range" min="0" max="20" value="{{$candidates_qualifications_range}}"
						class="candidates-qualifications-range section-sidebar-alpha-item-content-handle profile-item-handle form-range" />
				</div>
				<div class="section-sidebar-alpha-item-button">
					<button class="btn candidates-qualifications-btn">{{__('message.filter')}}</button>
				</div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="section-sidebar-alpha-item">
				<div class="section-sidebar-alpha-item-heading">
					<i class="fa-icon fa-solid fa-trophy"></i> <h3>{{__('message.achievements')}}</h3>
				</div>
				<div class="section-sidebar-alpha-item-content">
					<label class="form-label">{{__('message.keywords')}}</label>
					<input type="text" name="" class="candidates-achievements-value" value="{{$candidates_achievements_value}}" />
				</div>
				<div class="section-sidebar-alpha-item-content">
					<label class="form-label">{{__('message.minimum_items')}} <span class="profile-item-value">(0)</span></label>
					<input type="range" min="0" max="20" value="{{$candidates_achievements_range}}"
						class="candidates-achievements-range section-sidebar-alpha-item-content-handle profile-item-handle form-range" />
				</div>
				<div class="section-sidebar-alpha-item-button">
					<button class="btn candidates-achievements-btn">{{__('message.filter')}}</button>
				</div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="section-sidebar-alpha-item">
				<div class="section-sidebar-alpha-item-heading">
					<i class="fa-icon fa-solid fa-screwdriver-wrench"></i> <h3>{{__('message.skills')}}</h3>
				</div>
				<div class="section-sidebar-alpha-item-content">
					<label class="form-label">{{__('message.keywords')}}</label>
					<input type="text" name="" class="candidates-skills-value" value="{{$candidates_skills_value}}" />
				</div>
				<div class="section-sidebar-alpha-item-content">
					<label class="form-label">{{__('message.minimum_items')}} <span class="profile-item-value">(0)</span></label>
					<input type="range" min="0" max="20" value="{{$candidates_skills_range}}"
						class="candidates-skills-range section-sidebar-alpha-item-content-handle profile-item-handle form-range" />
				</div>
				<div class="section-sidebar-alpha-item-button">
					<button class="btn candidates-skills-btn">{{__('message.filter')}}</button>
				</div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="section-sidebar-alpha-item">
				<div class="section-sidebar-alpha-item-heading">
					<i class="fa-icon fa-solid fa-language"></i> <h3>{{__('message.languages')}}</h3>
				</div>
				<div class="section-sidebar-alpha-item-content">
					<label class="form-label">{{__('message.keywords')}}</label>
					<input type="text" name="" class="candidates-languages-value" value="{{$candidates_languages_value}}" />
				</div>
				<div class="section-sidebar-alpha-item-content">
					<label class="form-label">{{__('message.minimum_items')}} <span class="profile-item-value">(0)</span></label>
					<input type="range" min="0" max="20" value="{{$candidates_languages_range}}"
						class="candidates-languages-range section-sidebar-alpha-item-content-handle profile-item-handle form-range" />
				</div>
				<div class="section-sidebar-alpha-item-button">
					<button class="btn candidates-languages-btn">{{__('message.filter')}}</button>
				</div>
			</div>
		</div>
	</div>
</div>
