class AdminController < ApplicationController

 before_filter :authenticate_user!
 before_filter :authenticate_admin!

  def index

  end

  private

  def authenticate_admin!
    unless current_user.id == 1
      flash[:error] = 'Your not an admin user'
      redirect_to root_path
    end
  end

end
