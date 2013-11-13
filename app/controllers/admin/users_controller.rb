class Admin::UsersController < AdminController
include Devise::Controllers::Helpers

 def index
    @users = User.paginate(:page => params[:page],:per_page => 10)

    respond_to do |format|
      format.html # index.html.erb
      format.json { render json: @users }
    end
end


# GET /resource/sign_up
  def new
    build_resource({})
    respond_with self.resource
  end

  # POST /resource
  def create
    build_resource(sign_up_params)

    if resource.save
      yield resource if block_given?
      if resource.active_for_authentication?
        set_flash_message :notice, :signed_up if is_flashing_format?
        sign_up(resource_name, resource)
        respond_with resource, :location => after_sign_up_path_for(resource)
      else
        set_flash_message :notice, :"signed_up_but_#{resource.inactive_message}" if is_flashing_format?
        expire_data_after_sign_in!
        respond_with resource, :location => after_inactive_sign_up_path_for(resource)
      end
    else
      clean_up_passwords resource
      respond_with resource
    end
  end

  # GET /resource/edit
  def edit
    @user = User.find(params[:id])
  end

  # PUT /resource
  # We need to use a copy of the resource because we don't want to change
  # the current user in place.
  def update
   @user = User.find(params[:id])
  #raise @user.inspect
  if @user.update_attributes(params[:user])
      #set_flash_message :notice, :updated
      # Sign in the user bypassing validation in case his password changed
      @user_c= User.find(current_user.id)
      sign_in @user_c, :bypass => true  
      respond_to do |format| 
        format.html { redirect_to edit_admin_user_path  , notice: "User (#{@user.email}) password  was successfully updated." }
        format.json
      end     
  else
respond_to do |format|
format.html { render action: "edit" , notice: "User was successfully deleted."}
format.json { render json: @event.errors, status: :unprocessable_entity }
 end
 end

end

 


  def destroy
    @user = User.find(params[:id])
    name=@user.email
    @user.destroy

    respond_to do |format|
      format.html { redirect_to admin_users_url , notice: "User (#{name}) was successfully deleted."}
      format.json { head :no_content }
    end
  end




end
