class Upload < ActiveRecord::Base
  # attr_accessible :title, :body
  attr_accessible :image
  has_attached_file :image, :styles => { :medium => "300x300>", :thumb => "267x175!>", :small => "80x59!>" }, :default_url => "default_upload_logo.gif"
  include Rails.application.routes.url_helpers

  def to_jq_upload
    {
      "name" => read_attribute(:image_file_name),
      "size" => read_attribute(:image_file_size),
      "url" => image.url(:original),
      "thumbnail_url" => image.url(:small),
      "delete_url" => upload_path(self),
      "delete_type" => "DELETE" 
    }
  end

 belongs_to :event
end
