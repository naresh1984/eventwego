class EventLanguage < ActiveRecord::Base
  attr_accessible :name
    has_many :events  , :dependent => :destroy
 validates :name,
            :uniqueness=> true,
            :presence => true
end
