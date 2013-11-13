class PasswordsController < ApplicationController

def edit 
     @today_count=today_count()
     @tomorrow_count=tomorrow_count()
     @thisweek_count=thisweek_count()
     @weekend_count=weekend_count()
     @nextmonth_count=nextmonth_count()
     @all_events=all_events()
     @all_types=all_types()
     @all_location=all_location()
     @typecount=0  
     @locount=0 
     @all_types.each do |ty|
     @typecount +=ty.counts.to_i
     end
     @all_location.each do |ty|
     @locount +=ty.counts.to_i
     end

  @user = User.find(current_user.id)
end

def update
    @page_title="Chnage Password"
    @user =User.find(params[:id])
     
    #raise @user.inspect
    encrypted_password = params[:user][:current_password]
    #raise encrypted_password.inspect
    if params[:current_password]== '' || params[:password]== '' || params[:password_confirmation]== ''
   
      redirect_to change_password_url, alert:'Please fill all fields'

    elsif  params[:password]!= params[:password_confirmation]

     redirect_to edit_password_path, alert:"Password doesn't match confirmation"

    elsif @user and @user.authenticate(encrypted_password) and @user.update_attributes(:password => params[:password])

     redirect_to change_password_url, alert: "Password successfully updated"

    else

        redirect_to change_password_url, alert: 'Invalid old password'

    end


  end


end
